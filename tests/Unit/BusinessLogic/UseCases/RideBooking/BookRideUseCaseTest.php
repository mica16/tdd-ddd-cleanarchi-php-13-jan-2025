<?php

namespace App\Tests\Unit\BusinessLogic\UseCases\RideBooking;

use App\BusinessLogic\UseCases\RideBooking\BookRideUseCase;
use App\Models\FakeRideDistanceCalculator;
use App\Models\Ride;
use App\Repositories\FakeRideRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BookRideUseCaseTest extends TestCase
{

    private FakeRideRepository $rideRepository;
    private FakeRideDistanceCalculator $rideDistanceCalculator;
    private string $rideId = "123abc";

    public function setUp(): void
    {
        $this->rideRepository = new FakeRideRepository();
        $this->rideDistanceCalculator = new FakeRideDistanceCalculator();
    }

    public static function distinctTrips(): \Generator
    {
        yield 'intra_muros_1km' => ['PARIS_ADDRESS', 'PARIS_ADDRESS_2', 1, 30.5];
        yield 'intra_muros_2km' => ['PARIS_ADDRESS', 'PARIS_ADDRESS_2', 2, 31];
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
        new BookRideUseCase($this->rideRepository, $this->rideDistanceCalculator)
            ->execute($departure, $arrival);
    }

    public function assertBookedRides(Ride $ride): void
    {
        $this->assertEquals([$ride], $this->rideRepository->rides);
    }

}