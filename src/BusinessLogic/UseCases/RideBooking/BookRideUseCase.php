<?php

namespace App\BusinessLogic\UseCases\RideBooking;

use App\Models\DateTimeProvider;
use App\Models\Ride;
use App\Models\BasePriceEvaluator;
use App\Models\RiderRepository;
use App\Repositories\RideRepository;
use App\Models\RideDistanceCalculator;

readonly class BookRideUseCase
{

    public function __construct(private RideRepository         $rideRepository,
                                private RiderRepository        $riderRepository,
                                private RideDistanceCalculator $rideDistanceCalculator,
                                private BasePriceEvaluator     $basePriceEvaluator,
                                private DateTimeProvider       $dateTimeProvider)
    {
    }

    public
    function execute(string $departure, string $arrival, bool $wantsUberX = false): void
    {
        $rider = $this->riderRepository->byId("456def");
        $isRiderBirthday = $rider->isBirthday($this->dateTimeProvider->now());
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