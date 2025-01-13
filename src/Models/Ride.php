<?php

namespace App\Models;

class Ride
{

    public function __construct(private string $id,
                                private string $departure,
                                private string $arrival,
                                private float  $price)
    {
    }

    public static function book(Rider     $rider,
                                string    $departure,
                                string    $arrival,
                                \DateTime $currentDate,
                                float     $basePrice,
                                float     $distance,
                                bool      $wantsUberX): Ride
    {
        $isRiderBirthday = $rider->isBirthday($currentDate);
        $price = $basePrice + $distance * 0.5;
        if ($wantsUberX) {
            if (!$isRiderBirthday) {
                $price += 10;
            }
            if ($distance < 3) {
                throw new \Exception("Cannot book an UberX ride for a short trip");
            }
        }
        return new Ride("123abc", $departure, $arrival, $price);
    }

}