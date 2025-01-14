<?php

namespace App\BusinessLogic\Models;

class Ride
{

    public function __construct(private string $id,
                                private string $departure,
                                private string $arrival,
                                private float  $price)
    {
    }

    public static function book(Rider     $rider,
                                Trip      $trip,
                                float     $basePrice,
                                OptionsPricingStrategy $optionsPricingStrategy): Ride
    {
        $tripPrice = $basePrice + $trip->computeKilometersFee();
        $optionsPrice = $optionsPricingStrategy->apply($trip, $rider);
        return new Ride("123abc", $trip->getDeparture(), $trip->getArrival(), $tripPrice + $optionsPrice);
    }

}