<?php

namespace App\BusinessLogic\Gateways\Providers;

interface TripScanner {
    function distanceBetween(string $departure, string $arrival): float;
    function isAddressInParis(string $address): bool;
}