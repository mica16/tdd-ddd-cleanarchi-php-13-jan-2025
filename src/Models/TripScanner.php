<?php

namespace App\Models;

interface TripScanner {
    function distanceBetween(string $departure, string $arrival): float;
    function isAddressInParis(string $address): bool;
}