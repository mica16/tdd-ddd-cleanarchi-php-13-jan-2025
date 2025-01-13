<?php

namespace App\Models;

interface RideDistanceCalculator {
    function calculate(string $departure, string $arrival): float;
}