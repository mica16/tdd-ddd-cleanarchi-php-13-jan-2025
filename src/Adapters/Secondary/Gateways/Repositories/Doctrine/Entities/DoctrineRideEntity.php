<?php

namespace App\Adapters\Secondary\Gateways\Repositories\Doctrine\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'rides')]
class DoctrineRideEntity {

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $departure;

    #[ORM\Column(type: 'string')]
    private string $arrival;

    #[ORM\Column(type: 'boolean')]
    private bool $uberX;

    #[ORM\Column(type: 'float')]
    private float $price;

    /**
     * @param int $id
     * @param string $departure
     * @param string $arrival
     * @param bool $uberX
     * @param float $price
     */
    public function __construct(string $id, string $departure, string $arrival, bool $uberX, float $price)
    {
        $this->id = $id;
        $this->departure = $departure;
        $this->arrival = $arrival;
        $this->uberX = $uberX;
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDeparture(): string
    {
        return $this->departure;
    }

    public function getArrival(): string
    {
        return $this->arrival;
    }

    public function isUberX(): bool
    {
        return $this->uberX;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

}