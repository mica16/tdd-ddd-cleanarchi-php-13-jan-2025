<?php

namespace App\Models;

interface RiderRepository {
    public function byId(string $riderId): Rider;
}