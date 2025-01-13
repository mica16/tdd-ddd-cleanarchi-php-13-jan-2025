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
    function execute(string $departure, string $arrival, bool $wantsUberX = false, bool $isRiderBirthday = false): void
    {
        $distance = $this->rideDistanceCalculator->calculate($departure, $arrival);
        $basePrice = $this->basePriceEvaluator->evaluate($departure, $arrival);
        $price = $basePrice + $distance * 0.5;
        if ($wantsUberX) {
            if (!$isRiderBirthday) {
                    $price += 10;
            }
            if ($distance < 3) {
                throw new \Exception("Cannot book an UberX ride for a short trip");
            }
        }

        $this->rideRepository->save(new Ride("123abc",
            $departure,
            $arrival,
            $price));
    }
}