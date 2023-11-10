<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;
use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription implements JsonSerializable
{

    // protected RouterInterface $router;

    // public function __construct(RouterInterface $router){
    //     $this->router = $router;
    // }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected int $id;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    protected string $name;

    #[ORM\Column(name: 'start_date', type: Types::DATE_IMMUTABLE)]
    protected \DateTimeImmutable $startDate;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    private ?PaymentType $paymentType = null;



    public function jsonSerialize(): mixed
    {
        $returnValue = [
            'type' => 'subscription',
            'id' => $this->getId(),     //alternative call
            'attributes' => [
                'name' => $this->name,
                'startDate' => $this->startDate,
            ],
            'links' => [
                'self' => '/subscriptions/' . $this->id, 
            ],
        ];

        if($this->getPaymentType()){
            $this->addPaymentTypeRelation($returnValue);
        }

        return $returnValue;
    }

    public function getId(): int{
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStartDate() : \DateTimeImmutable 
    {
        return $this->startDate;    
    }

    public function setStartDate(\DateTimeImmutable $startDate) : self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    protected function addPaymentTypeRelation(array &$returnValue)
    {
        $returnValue['relationships'] = [
                        'paymentType' => [
                            'links' => [
                                'related' => '/paymentType/' . $this->getPaymentType()->getId(),
                            ]
                        ]
                    ];   
                    
    }
}