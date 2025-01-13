<?php

namespace App\Models;

class FakeRiderRepository implements RiderRepository
{

    private array $riders = [];

    public function byId(string $riderId): Rider
    {
        return $this->riders[$riderId];
    }

    public function feedWith(Rider $rider): void
    {
        $this->riders[$rider->getId()] = $rider;
    }
}