<?php

namespace App\Adapters\Secondary\Gateways\Providers\TripScanning;

use App\BusinessLogic\Gateways\Providers\TripScanner;
use yidas\googleMaps\Client;

class GoogleTripScanner implements TripScanner
{
    private Client $client;

    public function __construct(private readonly string $apiKey)
    {
        $this->client = new Client(['key' => $this->apiKey]);
    }

    public function distanceBetween(string $departure, string $arrival): float
    {
        $response = $this->client->distanceMatrix(
            $departure,
            $arrival,
            ['mode' => 'driving']
        );

        if ($response['status'] === 'OK') {
            $element = $response['rows'][0]['elements'][0];
            if ($element['status'] === 'OK') {
                // Distance in meters; convert to kilometers
                return $element['distance']['value'] / 1000;
            }
        }
        return 0;
    }

    function isAddressInParis(string $address): bool
    {
        $response = $this->client->geocode($address);

        if (!empty($response[0])) {
            $addressComponents = $response[0]['address_components'];

            $isParis = false;
            $postalCode = null;

            foreach ($addressComponents as $component) {
                // Check for locality or administrative area
                if (in_array('locality', $component['types']) || in_array('administrative_area_level_2', $component['types'])) {
                    if (strcasecmp($component['long_name'], 'Paris') === 0) {
                        $isParis = true;
                    }
                }

                // Capture the postal code if available
                if (in_array('postal_code', $component['types'])) {
                    $postalCode = $component['long_name'];
                }
            }

            // Check if postal code starts with 75 (Paris range)
            if ($postalCode && preg_match('/^75\d{3}$/', $postalCode)) {
                return true;
            }

            // Fallback to locality check
            return $isParis;
        }

        return false;
    }
}