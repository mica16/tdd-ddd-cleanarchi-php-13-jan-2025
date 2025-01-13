<?php

namespace App\Models;

class FakeRideDistanceCalculator implements RideDistanceCalculator {

    public float $distance = -1;

    public function calculate(string $departure, string $arrival): float
    {
        return $this->distance;
    }

}