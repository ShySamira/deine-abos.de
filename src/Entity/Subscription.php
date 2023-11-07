<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Subscription implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\Column()]
    protected string $name;

    #[ORM\Column(name: 'start_date', type: Types::DATE_IMMUTABLE)]
    protected \DateTimeImmutable $startDate;

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'startDate' => $this->startDate,
        ];
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

}