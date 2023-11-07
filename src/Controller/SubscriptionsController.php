<?php

namespace App\Controller;

use App\Model\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionsController extends AbstractController
{
 public function list():Response
 {
    $dataArray = [
        'success' => true,
        'subscriptions' => $this->generateSubscriptions(),
    ];

    return $this->json($dataArray);
 }

 protected function generateSubscriptions() : array
 {
    $returnArray = [];
    
    $returnArray[] = (new Subscription)
        ->setName('Crunchyroll')
        ->setStartDate(new \DateTime());

    $returnArray[] = (new Subscription)
        ->setName('AnimeOnDemand')
        ->setStartDate(new \DateTime());

    $returnArray[] = (new Subscription)
        ->setName('Netflix')
        ->setStartDate(new \DateTime());

    return $returnArray;
 }
}