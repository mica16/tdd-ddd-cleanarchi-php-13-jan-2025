<?php

namespace App\BusinessLogic\UseCases\RideBooking;

use App\BusinessLogic\Gateways\Providers\TripScanner;
use App\BusinessLogic\Gateways\Repositories\RideRepository;
use App\BusinessLogic\Gateways\Repositories\RiderRepository;
use App\BusinessLogic\Models\BasePriceEvaluator;
use App\BusinessLogic\Models\DateTimeProvider;
use App\BusinessLogic\Models\Ride;
use App\BusinessLogic\Models\Trip;

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
        $trip = $this->createTrip($departure, $arrival);
        $basePrice = $this->basePriceEvaluator->evaluate($trip);

        $ride = Ride::book(
            $rider,
            $trip,
            $this->dateTimeProvider->now(),
            $basePrice,
            $wantsUberX
        );
        $this->rideRepository->save($ride);
    }

    private function createTrip(string $departure, string $arrival): Trip
    {
        $distance = $this->tripScanner->distanceBetween($departure, $arrival);
        $isDepartureInParis = $this->tripScanner->isAddressInParis($departure);
        $isArrivalInParis = $this->tripScanner->isAddressInParis($arrival);
        return Trip::create($departure, $arrival, $distance, $isDepartureInParis, $isArrivalInParis);
    }
}