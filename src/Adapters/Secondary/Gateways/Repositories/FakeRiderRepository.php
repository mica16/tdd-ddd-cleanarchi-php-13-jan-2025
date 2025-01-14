<?php

namespace App\Adapters\Secondary\Gateways\Repositories;

use App\BusinessLogic\Gateways\Repositories\RiderRepository;
use App\BusinessLogic\Models\Rider;

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