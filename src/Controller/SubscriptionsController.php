<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PaymentType;
use App\Entity\Subscription;
use App\Serializer\SubscriptionNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionsController extends AbstractController
{
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function list(RouterInterface $router, SubscriptionNormalizer $subscriptionNormalizer):Response
    {
        $serializer = new Serializer([$subscriptionNormalizer], []);

        $subscriptions = $this->doctrine->getRepository(Subscription::class)->findAll();

        if(!$subscriptions){
            return $this->json(['success' => false], 404);
        }

        dd($this->container->get('app.serializer.subscription_normalizer'));
        foreach($subscriptions as $subscription){
            $array = $serializer->normalize($subscription, null, ['circular_reference_handler' => function ($object){
                return $object->getId();
            }]);

            $subscriptionsColletion[] = $array;
        }

        $dataArray = [
            'data' => $subscriptionsColletion,
            'link' => $router->generate('listSubscriptions'),
        ];

        return $this->json($dataArray);
    }

    public function create(Request $request, ValidatorInterface $validator):Response
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

    public function read(int $id): Response
    {
        $subscription = $this->doctrine->getRepository(Subscription::class)->find($id);

        if(!$subscription){
            return $this->json([], 400);
        }

        return $this->json(['data' => $subscription]);
    }

    public function update(int $id, Request $request, ValidatorInterface $validator) : Response 
    {
        $subscription = $this->doctrine->getRepository(Subscription::class)->find($id);
        $paymentTypeId = (int)$request->request->get('paymentType');
        //$request->request->set('paymentType', );
        $request->request->remove('paymentType');
        $paymentType = $this->doctrine->getRepository(PaymentType::class)->find($paymentTypeId);

        if(!$subscription){
            return $this->json([], 400);
        }


        // //Lade (Temporär) PaymentType
        // $paymentTypeId = (int)$request->request->get('paymentType');

        // if($paymentTypeId)
        // {
        //     $paymentType = $this->doctrine->getRepository(PaymentType::class)->find($paymentTypeId);

        //     if($paymentType)
        //     {
        //         $request->request->set('paymentType', $paymentType->getName());
        //     }else
        //     {
        //         $request->request->remove('paymentType');
        //     }
        // }

        $requestData = $request->request->all();
        $requestData['PaymentType'] = $paymentType;
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