<?php

namespace App\Models;

interface BasePriceEvaluator {
    function evaluate(string $departure, string $arrival): float;
}