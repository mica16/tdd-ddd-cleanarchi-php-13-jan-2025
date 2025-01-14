<?php

use App\BusinessLogic\Gateways\Providers\TripScanner;

class GoogleTripScanner implements TripScanner
{
    public function __construct(private string $apiKey)
    {
    }

    public function distanceBetween(string $departure, string $arrival): float
    {
    }

    function isAddressInParis(string $address): bool
    {
        // TODO: Implement isAddressInParis() method.
    }
}