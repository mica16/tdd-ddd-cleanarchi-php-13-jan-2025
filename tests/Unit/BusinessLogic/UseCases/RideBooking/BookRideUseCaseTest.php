<?php

namespace App\Tests\Unit\BusinessLogic\UseCases\RideBooking;

use App\Models\Ride;
use PHPUnit\Framework\TestCase;
use App\Models\FakeBasePriceEvaluator;
use PHPUnit\Framework\Attributes\Test;
use App\Repositories\FakeRideRepository;
use App\Models\FakeRideDistanceCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use App\BusinessLogic\UseCases\RideBooking\BookRideUseCase;

class BookRideUseCaseTest extends TestCase
{

    private FakeRideRepository $rideRepository;
    private FakeRideDistanceCalculator $rideDistanceCalculator;
    private FakeBasePriceEvaluator $basePriceEvaluator;
    private string $rideId = "123abc";

    public function setUp(): void
    {
        $this->rideRepository = new FakeRideRepository();
        $this->rideDistanceCalculator = new FakeRideDistanceCalculator();
        $this->basePriceEvaluator = new FakeBasePriceEvaluator();
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
        $this->rideDistanceCalculator->distance = $distance;
        $this->bookRide($departure, $arrival);
        $this->assertBookedRides(new Ride($this->rideId, $departure, $arrival, $expectedPrice));
    }

    #[Test]
    public function should_be_charged_for_a_uber_x_ride_in_case_of_long_trip_and_no_birthday(): void
    {
        $this->rideDistanceCalculator->distance = 3;
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true);
        $this->assertBookedRides(new Ride($this->rideId,
            'PARIS_ADDRESS', 'PARIS_ADDRESS', 41.5));
    }

    #[Test]
    public function cannot_book_a_uber_x_ride_in_case_of_short_trip(): void
    {
        $this->rideDistanceCalculator->distance = 1;
        $this->expectException(\Exception::class);
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true);
        $this->assertBookedRides();
    }

    #[Test]
    public function should_be_offered_a_uber_x_ride_in_case_of_long_trip_and_birthday(): void
    {
        $this->rideDistanceCalculator->distance = 3;
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true, isRiderBirthday: true);
        $this->assertBookedRides(new Ride($this->rideId,
            'PARIS_ADDRESS', 'PARIS_ADDRESS', 31.5));
    }

    #[Test]
    public function cannot_book_a_uber_x_ride_in_case_of_short_trip_and_birthday(): void
    {
        $this->rideDistanceCalculator->distance = 1;
        $this->expectException(\Exception::class);
        $this->bookRide('PARIS_ADDRESS', 'PARIS_ADDRESS', true, isRiderBirthday: true);
        $this->assertBookedRides();
    }

    public function bookRide(string $departure, string $arrival, bool $wantsUberX = false, $isRiderBirthday = false): void
    {
        new BookRideUseCase($this->rideRepository, $this->rideDistanceCalculator, $this->basePriceEvaluator)
            ->execute($departure, $arrival, $wantsUberX, $isRiderBirthday);
    }

    public function assertBookedRides(Ride... $rides): void
    {
        $this->assertEquals($rides, $this->rideRepository->rides);
    }

}