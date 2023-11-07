<?php

declare(strict_types=1);

namespace App\Entity;

use JsonSerializable;

/**
 * @ORM\Enity()
 */
class Subscription implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type='integer')
     */
    protected int $id;

    /**
     * @ORM\Column(type='string', length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type='datetime')
     */
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