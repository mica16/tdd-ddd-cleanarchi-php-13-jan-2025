<?php

namespace App\Adapters\Secondary\Gateways\Repositories;

use App\BusinessLogic\Gateways\Repositories\RideRepository;
use App\BusinessLogic\Models\Ride;

class FakeRideRepository implements RideRepository
{

    public array $rides = [];

    public function save(Ride $ride): void
    {
        $this->rides[] = $ride;
    }
}