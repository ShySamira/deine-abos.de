<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PaymentType;
use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionsController extends AbstractController
{
    
    public function __construct(private ManagerRegistry $doctrine)
    {

    }

    public function list():Response
    {

        $subscriptions = $this->doctrine->getRepository(Subscription::class)->findAll();

        if(!$subscriptions){
            return $this->json(['success' => false], 404);
        }
        $dataArray = [
            'success' => true,
            'subscriptions' => $subscriptions,
        ];

        return $this->json($dataArray);
    }

    public function add(Request $request, ValidatorInterface $validator):Response
    {
        $subscriptionName = $request->request->get('name');

        $subscription = (new Subscription())->setName($subscriptionName)->setStartDate(new \DateTimeImmutable());
        $error = $validator->validate($subscription);

        if(0 !== count($error)){
            $errorMessages = [];

            foreach($error as $violation){
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }

            return $this->json(['success' => false, 'errors' => $errorMessages], 400);
        }

        $em = $this->doctrine->getManager();
        $em->persist($subscription);
        $em->flush();

        return $this->json(['success' => true, 'data' => $subscription], 201);
    }

    public function update(int $id, Request $request, ValidatorInterface $validator) : Response 
    {
        $subscription = $this->doctrine->getRepository(Subscription::class)->find($id);

        if(!$subscription){
            return $this->json([], 400);
        }

        //Lade (Temporär) PaymentType
        $paymentTypeId = (int)$request->request->get('paymentType');

        if($paymentTypeId)
        {
            $paymentType = $this->doctrine->getRepository(PaymentType::class)->find($paymentTypeId);

            if($paymentType)
            {
                $request->request->set('paymentType', $paymentType);
            }else
            {
                $request->request->remove('paymentType');
            }
        }
        $requestData = $request->request->all();

        $this->setDataToPaymentType($requestData, $subscription);

        $error = $validator->validate($subscription);

        if(0 !== count($error)){
            $errorMessages = [];

            foreach($error as $violation){
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }

            return $this->json(['success' => false, 'errors' => $errorMessages], 400);
        }

        $em = $this->doctrine->getManager();
        $em->flush();

        return $this->json(['success' => true, 'data' => $subscription], 201);
    }

    protected function setDataToPaymentType(array $requestData, object $subscription){

        foreach($requestData as $key => $value){
            $methodName = 'set' . ucfirst($key);
            if(!empty($requestData) && method_exists($subscription, $methodName)){
                method_exists($subscription, 'set' . ucfirst($key));
                $subscription->{$methodName}($value);
            }
        }
    }
}