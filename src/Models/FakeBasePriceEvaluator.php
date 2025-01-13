<?php

namespace App\Models;

class FakeBasePriceEvaluator implements BasePriceEvaluator {

    private array $basePricesPerDirections = [
        "PARIS => PARIS" => 30,
        "OUTSIDE => PARIS" => 50,
        "PARIS => OUTSIDE" => 20,
        "OUTSIDE => OUTSIDE" => 100
    ];

    public function evaluate(string $tripDirection): float
    {
        return $this->basePricesPerDirections[$tripDirection];
    }
}