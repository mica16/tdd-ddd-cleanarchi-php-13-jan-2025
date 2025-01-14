<?php

namespace App\Adapters\Secondary\Gateways\Providers;

use App\BusinessLogic\Models\BasePriceEvaluator;
use App\BusinessLogic\Models\Trip;

class FakeBasePriceEvaluator implements BasePriceEvaluator {

    private array $basePricesPerDirections = [
        "PARIS => PARIS" => 30,
        "OUTSIDE => PARIS" => 50,
        "PARIS => OUTSIDE" => 20,
        "OUTSIDE => OUTSIDE" => 100
    ];

    public function evaluate(Trip $trip): float
    {
        return $this->basePricesPerDirections[$trip->getTripDirection()];
    }
}