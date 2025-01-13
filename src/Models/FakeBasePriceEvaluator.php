<?php

namespace App\Models;

class FakeBasePriceEvaluator implements BasePriceEvaluator {

    public function evaluate(string $departure, string $arrival): float
    {
        $isDepartureFromParis = str_contains(strtolower($departure), 'paris');
        $isArrivalToParis = str_contains(strtolower($arrival), 'paris');
        return match(true)
        {
            $isDepartureFromParis && !$isArrivalToParis =>  20,
            !$isDepartureFromParis && !$isArrivalToParis =>  100,
            !$isDepartureFromParis && $isArrivalToParis =>  50,
            default =>  30,
        };
    }
}