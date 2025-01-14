<?php

namespace App\BusinessLogic\Models;

interface BasePriceEvaluator {
    function evaluate(Trip $trip): float;
}