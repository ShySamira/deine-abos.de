<?php

declare(strict_types=1);

Namespace App\Serializer;

use App\Entity\Subscription;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubscriptionNormalizer implements NormalizerInterface
{
    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Subscription $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $returnData = [];
        $returnData['type'] = 'subscription';
        $returnData['id'] = $object->getId();
        $returnData['attributes'] = [
            'name' => $object->getName(),
            'startDate' => $object->getStartDate(),
            ];
        $returnData['links'] = [
            'self' => $this->router->generate('readSubscription', ['id' => $object->getId()])
        ];

        $this->createRelationLinks($object, $returnData);

        return $returnData;
    }
   
    public function supportsNormalization($data, string $format = null, array $context =[])
    {
        return $data instanceof Subscription;
    }

    public function createRelationLinks(Subscription $subscription, array &$returnData): void
    {
        if($subscription->getPaymentType())
        {
            $returnData['relationships'] = [
                'paymentType' => [
                    'links' => [
                        'related' => $this->router->generate('readPaymentType', ['id' => $subscription->getPaymentType()->getId()]),
                    ]
                ]
            ];
    
        }
    }


}