<?php

namespace App\Tests\Unit\BusinessLogic\UseCases\RideBooking;

use App\Adapters\Secondary\Gateways\Providers\FakeBasePriceEvaluator;
use App\Adapters\Secondary\Gateways\Providers\TransactionPerforming\PassiveTransactionPerformer;
use App\Adapters\Secondary\Gateways\Providers\TripScanning\FakeTripScanner;
use App\Adapters\Secondary\Gateways\Repositories\FakeRideRepository;
use App\Adapters\Secondary\Gateways\Repositories\FakeRiderRepository;
use App\BusinessLogic\Gateways\Providers\TransactionPerformer;
use App\BusinessLogic\Models\DeterministicDateTimeProvider;
use App\BusinessLogic\Models\Ride;
use App\BusinessLogic\Models\Rider;
use App\BusinessLogic\UseCases\RideBooking\BookRideCommand;
use App\BusinessLogic\UseCases\RideBooking\BookRideCommandHandler;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BookRideCommandHandlerTest extends TestCase
{

    private FakeRideRepository $rideRepository;
    private FakeRiderRepository $riderRepository;
    private FakeTripScanner $tripScanner;
    private FakeBasePriceEvaluator $basePriceEvaluator;
    private DeterministicDateTimeProvider $dateTimeProvider;
    private TransactionPerformer $transactionPerformer;
    private string $rideId = "123abc";

    public function setUp(): void
    {
        $this->rideRepository = new FakeRideRepository();
        $this->riderRepository = new FakeRiderRepository();
        $this->tripScanner = new FakeTripScanner();
        $this->transactionPerformer = new PassiveTransactionPerformer();
        $this->basePriceEvaluator = new FakeBasePriceEvaluator();
        $this->dateTimeProvider = new DeterministicDateTimeProvider();
        $this->dateTimeProvider->currentDate = new \DateTime('2021-10-21');
        $this->riderRepository->feedWith(new Rider('456def', new \DateTime('1990-02-03')));
    }

    public static function distinctTrips(): \Generator
    {
        yield 'intra_muros_1km' => ['PARIS_ADDRESS', 'PARIS_ADDRESS_2', 1, 30.5];
        yield 'intra_muros_2km' => ['PARIS_ADDRESS', 'PARIS_ADDRESS_2', 2, 31];
        yield 'paris_to_extra_muros_1km' => ['PARIS_ADDRESS', 'EXTRA_MUROS_ADDRESS', 1, 20.5];
        yield 'extra_muros_0km' => ['EXTRA_MUROS_ADDRESS', 'EXTRA_MUROS_ADDRESS', 0, 100];
        yield 'extra_muros_to_paris_1km' => ['EXTRA_MUROS_ADDRESS', 'PARIS_ADDRESS', 1, 50.5];
    }

    #[DataProvider("distinctTrips")]
    #[Test]
    public function should_book_a_basic_ride_successfully(
        string $departure,
        string $arrival,
        float  $distance,
        float  $expectedPrice
    ): void
    {
        $this->tripScanner->distance = $distance;
        $this->bookRide($departure, $arrival);
        $this->assertBookedRides(new Ride($this->rideId, $departure, $arrival, false, $expectedPrice));
    }

    #[Test]
    public function should_be_charged_for_a_uber_x_ride_in_case_of_long_trip_and_no_birthday(): void
    {
        $this->tripScanner->distance = 3;
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true);
        $this->assertBookedRides(new Ride($this->rideId,
            'PARIS_ADDRESS', 'PARIS_ADDRESS', true, 41.5));
    }

    #[Test]
    public function cannot_book_a_uber_x_ride_in_case_of_short_trip(): void
    {
        $this->tripScanner->distance = 1;
        $this->expectException(\Exception::class);
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true);
        $this->assertBookedRides();
    }

    #[Test]
    public function should_be_offered_a_uber_x_ride_in_case_of_long_trip_and_birthday(): void
    {
        $this->dateTimeProvider->currentDate = new \DateTime('2021-02-03');
        $this->tripScanner->distance = 3;
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true);
        $this->assertBookedRides(new Ride($this->rideId,
            'PARIS_ADDRESS', 'PARIS_ADDRESS', true,31.5));
    }

    #[Test]
    public function cannot_book_a_uber_x_ride_in_case_of_short_trip_and_birthday(): void
    {
        $this->dateTimeProvider->currentDate = new \DateTime('2021-02-03');
        $this->tripScanner->distance = 1;
        $this->expectException(\Exception::class);
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS',true);
        $this->assertBookedRides();
    }

    public function bookRide(string $departure, string $arrival, bool $wantsUberX = false): void
    {
        new BookRideCommandHandler($this->rideRepository,
            $this->riderRepository,
            $this->tripScanner,
            $this->basePriceEvaluator,
            $this->dateTimeProvider,
            $this->transactionPerformer)
            ->__invoke(new BookRideCommand($departure, $arrival, $wantsUberX));
    }

    public function assertBookedRides(Ride... $rides): void
    {
        $this->assertEquals($rides, $this->rideRepository->rides);
    }

}