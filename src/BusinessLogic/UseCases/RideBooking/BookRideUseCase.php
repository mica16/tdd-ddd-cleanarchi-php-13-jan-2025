<?php

namespace App\BusinessLogic\UseCases\RideBooking;

use App\Models\DateTimeProvider;
use App\Models\Ride;
use App\Models\BasePriceEvaluator;
use App\Models\RiderRepository;
use App\Repositories\RideRepository;
use App\Models\TripScanner;

readonly class BookRideUseCase
{

    public function __construct(private RideRepository     $rideRepository,
                                private RiderRepository    $riderRepository,
                                private TripScanner        $tripScanner,
                                private BasePriceEvaluator $basePriceEvaluator,
                                private DateTimeProvider   $dateTimeProvider)
    {
    }

    public function execute(string $departure, string $arrival, bool $wantsUberX = false): void
    {
        $rider = $this->riderRepository->byId("456def");
        $distance = $this->tripScanner->distanceBetween($departure, $arrival);
        $isDepartureInParis = $this->tripScanner->isAddressInParis($departure);
        $isArrivalInParis = $this->tripScanner->isAddressInParis($arrival);
        $tripDirection = ($isDepartureInParis ? "PARIS" : "OUTSIDE") . " => " . ($isArrivalInParis ? "PARIS" : "OUTSIDE");
        $basePrice = $this->basePriceEvaluator->evaluate($tripDirection);

        $ride = Ride::book(
            $rider,
            $departure,
            $arrival,
            $this->dateTimeProvider->now(),
            $basePrice,
            $distance,
            $wantsUberX
        );
        $this->rideRepository->save($ride);
    }
}