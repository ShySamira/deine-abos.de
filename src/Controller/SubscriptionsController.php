<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class SubscriptionsController extends AbstractController
{
    
public function __construct(private ManagerRegistry $doctrine) {}

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

    $entityManager = $this->doctrine->getManager();
    $subscription1 = (new Subscription)
        ->setName('Crunchyroll')
        ->setStartDate(new \DateTimeImmutable());

    $entityManager->persist($subscription1);


    $subscription2 = (new Subscription)
        ->setName('AnimeOnDemand')
        ->setStartDate(new \DateTimeImmutable());

    $entityManager->persist($subscription2);

    $subscription3 = (new Subscription)
        ->setName('Netflix')
        ->setStartDate(new \DateTimeImmutable());

    $entityManager->persist($subscription3);

    $entityManager->flush();

    return $returnArray;
 }


}