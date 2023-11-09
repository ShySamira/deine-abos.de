<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PaymentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaymentTypeRepository::class)]
class PaymentType implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected int $id;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING)]
    protected string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    protected ?string $description;

    #[ORM\OneToMany(mappedBy: 'paymentType', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function jsonSerialize()
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description ?? '',
        ];
    }



    public function getId() : int
    {
        return $this->id;
    }


    public function getName() : string
    {
        return $this->name;
    }

    public function setName($name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription($description) : self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setPaymentType($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getPaymentType() === $this) {
                $subscription->setPaymentType(null);
            }
        }

        return $this;
    }
}