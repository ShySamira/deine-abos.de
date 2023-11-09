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
        $paymentType = new PaymentType();
        $this->setDataToPaymentType($request->request->all(),$paymentType);

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

        return $this->json(['success' => true, 'data' => $paymentType], 201);
    }

    public function read() : Response 
    {

        return $this->json([]);
    }

    public function update(int $id, Request $request, ValidatorInterface $validator) : Response 
    {
        $paymentType = $this->doctrine->getRepository(PaymentType::class)->find($id);

        if(!$paymentType){
            return $this->json([], 400);
        }

        $requestData = $request->request->all();

        $this->setDataToPaymentType($requestData, $paymentType);

        $error = $validator->validate($paymentType);

        if(0 !== count($error)){
            $errorMessages = [];

            foreach($error as $violation){
                $errorMessages[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
            }

            return $this->json(['success' => false, 'errors' => $errorMessages], 400);
        }

        $em = $this->doctrine->getManager();
        $em->flush();

        return $this->json(['success' => true, 'data' => $paymentType], 201);
    }

    public function delete() : Response 
    {

        return $this->json([]);
    }

    protected function setDataToPaymentType(array $requestData, object $paymentType){

        foreach($requestData as $key => $value){
            $methodName = 'set' . ucfirst($key);
            if(!empty($requestData) && method_exists($paymentType, $methodName)){
                method_exists($paymentType, 'set' . ucfirst($key));
                $paymentType->{$methodName}($value);
            }
        }
    }
}