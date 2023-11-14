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
class Subscription //implements JsonSerializable
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

    #[ORM\Column(nullable: true)]
    protected ?float $costs = null;

    #[ORM\Column(nullable: true)]
    protected ?int $period = null;

    #[ORM\ManyToOne]
    private ?Category $category = null;

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

    public function getCosts(): ?float
    {
        return $this->costs;
    }

    public function setCosts(?float $costs): static
    {
        $this->costs = $costs;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(?int $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}