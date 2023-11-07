<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionsController extends AbstractController
{
 public function list():Response
 {
    $dataArray = [
        'success' => true,
        'subscriptions' => [

        ]
    ];

    return $this->json($dataArray);
 }
}