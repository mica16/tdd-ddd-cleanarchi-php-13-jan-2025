<?php

namespace App\BusinessLogic\UseCases\RideBooking;

use App\Models\Ride;
use App\Models\BasePriceEvaluator;
use App\Repositories\RideRepository;
use App\Models\RideDistanceCalculator;

readonly class BookRideUseCase
{

    public function __construct(private RideRepository         $rideRepository,
                                private RideDistanceCalculator $rideDistanceCalculator,
                                private BasePriceEvaluator     $basePriceEvaluator)
    {
    }

    public
    function execute(string $departure, string $arrival): void
    {
        $distance = $this->rideDistanceCalculator->calculate($departure, $arrival);
        $basePrice = $this->basePriceEvaluator->evaluate($departure, $arrival);
        $price = $basePrice + $distance * 0.5;
        $this->rideRepository->save(new Ride("123abc",
            $departure,
            $arrival,
            $price));
    }
}