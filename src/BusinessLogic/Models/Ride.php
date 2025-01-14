<?php

namespace App\BusinessLogic\Models;

class Ride
{

    public function __construct(private string $id,
                                private string $departure,
                                private string $arrival,
                                private bool   $uberX,
                                private float  $price)
    {
    }

    public static function book(Rider     $rider,
                                Trip      $trip,
                                float     $basePrice,
                                bool $wantsUberX,
                                OptionsPricingStrategy $optionsPricingStrategy): Ride
    {
        $tripPrice = $basePrice + $trip->computeKilometersFee();
        $optionsPrice = $optionsPricingStrategy->apply($trip, $rider);
        return new Ride("123abc", $trip->getDeparture(), $trip->getArrival(), $wantsUberX, $tripPrice + $optionsPrice);
    }

    public function getId(): string
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function isUberX(): bool
    {
        return $this->uberX;
    }


}