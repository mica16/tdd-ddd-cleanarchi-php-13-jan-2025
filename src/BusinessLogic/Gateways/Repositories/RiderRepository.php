<?php

namespace App\BusinessLogic\Gateways\Repositories;

use App\BusinessLogic\Models\Rider;

interface RiderRepository {
    public function byId(string $riderId): Rider;
}