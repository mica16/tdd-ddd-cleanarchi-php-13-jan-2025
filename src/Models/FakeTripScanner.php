<?php

namespace App\Models;

class FakeTripScanner implements TripScanner {

    public float $distance = -1;

    public function distanceBetween(string $departure, string $arrival): float
    {
        return $this->distance;
    }

    public function isAddressInParis(string $address): bool
    {
        return str_contains(strtolower($address), 'paris');
    }

}