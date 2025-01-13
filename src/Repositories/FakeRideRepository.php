<?php

namespace App\Repositories;

use App\Models\Ride;

class FakeRideRepository implements RideRepository
{

    public array $rides = [];

    public function save(Ride $ride): void
    {
        $this->rides[] = $ride;
    }
}