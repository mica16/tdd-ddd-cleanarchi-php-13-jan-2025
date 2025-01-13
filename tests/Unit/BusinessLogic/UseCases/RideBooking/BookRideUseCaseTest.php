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
    public function should_book_a_ride_successfully(
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

    public function bookRide(string $departure, string $arrival): void
    {
        new BookRideUseCase($this->rideRepository, $this->rideDistanceCalculator, $this->basePriceEvaluator)
            ->execute($departure, $arrival);
    }

    public function assertBookedRides(Ride $ride): void
    {
        $this->assertEquals([$ride], $this->rideRepository->rides);
    }

}