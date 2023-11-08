<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscription;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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

 public function add(Request $request):Response
 {
    $subscriptionName = $request->request->get('name');

    if(is_string($subscriptionName))
    {
        $subscription = (new Subscription())->setName($subscriptionName)->setStartDate(new \DateTimeImmutable());

        $em = $this->doctrine->getManager();
        $em->persist($subscription);
        $em->flush();
    
        if($subscription->getId())
        {
            return $this->json(['success' => true, 'subscription' => $subscription], 201);
        }
    }

    return $this->json(['success' => false], 400);
 }
}