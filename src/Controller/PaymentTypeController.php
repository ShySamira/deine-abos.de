<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PaymentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentTypeController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {

    }

    public function list() : Response 
    {
        $paymentTypes = $this->doctrine->getRepository(PaymentType::class)->findAll();

        return $this->json(['paymentTypes' => $paymentTypes]);
    }

    public function create(Request $request, ValidatorInterface $validator) : Response 
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');

        $paymentType = (new PaymentType())->setName($name)->setDescription($description);
        $error = $validator->validate($paymentType);

        if(0 !== count($error)){
            $errorMessages = [];

            foreach($error as $violation){
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }

            return $this->json(['success' => false, 'errors' => $errorMessages], 400);
        }

        $em = $this->doctrine->getManager();
        $em->persist($paymentType);
        $em->flush();

        return $this->json(['success' => true, 'paymentType' => $paymentType], 201);
    }

    public function read() : Response 
    {

        return $this->json([]);
    }

    public function update() : Response 
    {

        return $this->json([]);
    }

    public function delete() : Response 
    {

        return $this->json([]);
    }
}