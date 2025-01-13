<?php

namespace App\Repositories;

use App\Models\Ride;

interface RideRepository {
    function save(Ride $ride): void;
}