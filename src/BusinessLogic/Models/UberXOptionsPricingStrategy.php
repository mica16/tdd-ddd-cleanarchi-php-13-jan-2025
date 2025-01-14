<?php

namespace App\BusinessLogic\Models;

readonly class UberXOptionsPricingStrategy implements OptionsPricingStrategy
{

    public function __construct(private DateTimeProvider $dateTimeProvider)
    {
    }

    public function apply(Trip $trip, Rider $rider): int
    {
        if ($trip->getDistance() < 3) {
            throw new \Exception("Cannot book an UberX ride for a short trip");
        }
        if (!$rider->isBirthday($this->dateTimeProvider->now())) {
            return 10;
        }
        return 0;
    }
}