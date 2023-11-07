<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    public function index(Request $request):Response
    {
        $response = new Response();

        $response->setContent("<p>HalloWelt</p>");

        return $response;
    }
}