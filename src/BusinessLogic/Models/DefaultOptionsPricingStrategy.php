<?php

namespace App\BusinessLogic\Models;

readonly class DefaultOptionsPricingStrategy implements OptionsPricingStrategy
{

    public function apply(Trip $trip, Rider $rider): int
    {
        return 0;
    }
}