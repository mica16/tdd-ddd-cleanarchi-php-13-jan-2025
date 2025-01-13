<?php

namespace App\Models;

interface BasePriceEvaluator {
    function evaluate(string $tripDirection): float;
}