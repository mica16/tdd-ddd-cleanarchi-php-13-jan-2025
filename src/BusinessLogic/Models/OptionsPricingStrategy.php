<?php

namespace App\BusinessLogic\Models;

interface OptionsPricingStrategy  {
    public function apply(Trip $trip, Rider $rider);
}