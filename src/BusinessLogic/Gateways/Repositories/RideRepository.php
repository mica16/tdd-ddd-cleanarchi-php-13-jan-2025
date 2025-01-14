<?php

namespace App\BusinessLogic\Gateways\Repositories;

use App\BusinessLogic\Models\Ride;

interface RideRepository {
    function save(Ride $ride): void;
}