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
                                \DateTime $currentDate,
                                float     $basePrice,
                                bool      $wantsUberX): Ride
    {
        $isRiderBirthday = $rider->isBirthday($currentDate);
        $price = $basePrice + $trip->computeKilometersFee();
        if ($wantsUberX) {
            if (!$isRiderBirthday) {
                $price += 10;
            }
            if ($trip->getDistance() < 3) {
                throw new \Exception("Cannot book an UberX ride for a short trip");
            }
        }
        return new Ride("123abc", $trip->getDeparture(), $trip->getArrival(), $price);
    }

}