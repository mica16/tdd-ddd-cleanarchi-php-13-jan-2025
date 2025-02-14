<?php

namespace App\BusinessLogic\UseCases\RideBooking;

use App\BusinessLogic\Gateways\Providers\TransactionPerformer;
use App\BusinessLogic\Gateways\Providers\TripScanner;
use App\BusinessLogic\Gateways\Repositories\RideRepository;
use App\BusinessLogic\Gateways\Repositories\RiderRepository;
use App\BusinessLogic\Models\BasePriceEvaluator;
use App\BusinessLogic\Models\DateTimeProvider;
use App\BusinessLogic\Models\DefaultOptionsPricingStrategy;
use App\BusinessLogic\Models\Ride;
use App\BusinessLogic\Models\Trip;
use App\BusinessLogic\Models\UberXOptionsPricingStrategy;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class BookRideCommandHandler
{

    public function __construct(private RideRepository       $rideRepository,
                                private RiderRepository      $riderRepository,
                                private TripScanner          $tripScanner,
                                private BasePriceEvaluator   $basePriceEvaluator,
                                private DateTimeProvider     $dateTimeProvider,
                                private TransactionPerformer $transactionPerformer)
    {
    }

    public function __invoke(BookRideCommand $command): void
    {
        $departure = $command->departure;
        $arrival = $command->arrival;
        $wantsUberX = $command->wantsUberX;

        $rider = $this->riderRepository->byId("456def");
        $trip = $this->createTrip($departure, $arrival);
        $basePrice = $this->basePriceEvaluator->evaluate($trip);

        $optionsPricingStrategy = $wantsUberX ? new UberXOptionsPricingStrategy($this->dateTimeProvider) :
            new DefaultOptionsPricingStrategy();

        $ride = Ride::book(
            $rider,
            $trip,
            $basePrice,
            $wantsUberX,
            $optionsPricingStrategy
        );
        $this->transactionPerformer->perform(function () use ($ride) {
            $this->rideRepository->save($ride);
            // $this->domainEventRepository->save($ride->getDomainEvents());
        });
    }

    private function createTrip(string $departure, string $arrival): Trip
    {
        $distance = $this->tripScanner->distanceBetween($departure, $arrival);
        $isDepartureInParis = $this->tripScanner->isAddressInParis($departure);
        $isArrivalInParis = $this->tripScanner->isAddressInParis($arrival);
        return Trip::create($departure, $arrival, $distance, $isDepartureInParis, $isArrivalInParis);
    }
}