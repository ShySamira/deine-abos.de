<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PaymentTypeRepository;
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

    public function jsonSerialize()
    {
        return[
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
}