<?php

declare(strict_types=1);

namespace App\Model;

use JsonSerializable;

class Subscription implements JsonSerializable
{
    protected string $name;
    protected \DateTime $startDate;

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

    public function getStartDate() : \DateTime 
    {
        return $this->startDate;    
    }

    public function setStartDate(\DateTime $startDate) : self
    {
        $this->startDate = $startDate;
        return $this;
    }

}