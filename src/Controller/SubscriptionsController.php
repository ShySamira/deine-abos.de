<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PaymentType;
use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Serializer\SubscriptionNormalizer;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
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
        $subscriptions = $this->doctrine->getRepository(Subscription::class)->findAll();

        return $this->render('subscription/list.html.twig',[
            'subscriptions' => $subscriptions
        ]);
    }

    public function new(Request $request):Response
    {
        $subscription = new Subscription();
        $subscription->setStartDate(new DateTimeImmutable());

        $form = $this->createForm(SubscriptionType::class, $subscription);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->doctrine->getManager();
            $em->persist($subscription);
            $em->flush();
            return $this->redirectToRoute('listSubscriptions');
        }

        return $this->render('subscription/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function detail(int $id, Request $request): Response
    {
        $subscription = $this->doctrine->getRepository(Subscription::class)->find($id);

        $form = $this->createForm(SubscriptionType::class, $subscription);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->doctrine->getManager();
            $em->flush();
        }

        return $this->render('subscription/detail.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView()
        ]);
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