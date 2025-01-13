<?php

namespace App\BusinessLogic\UseCases\RideBooking;

use App\Models\Ride;
use App\Models\RideDistanceCalculator;
use App\Repositories\RideRepository;

readonly class BookRideUseCase
{

    public function __construct(private RideRepository         $rideRepository,
                                private RideDistanceCalculator $rideDistanceCalculator)
    {
    }

    public
    function execute(string $departure, string $arrival): void
    {
        $distance = $this->rideDistanceCalculator->calculate($departure, $arrival);
        $price = 30 + $distance * 0.5;
        $this->rideRepository->save(new Ride("123abc",
            $departure,
            $arrival,
            $price));
    }
}