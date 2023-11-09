<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;
use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription implements JsonSerializable
{

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
        return [
            'type' => 'subscription',
            'id' => $this->getId(),     //alternative call
            'attributes' => [
                'name' => $this->name,
                'startDate' => $this->startDate,
            ]
        ];
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


}