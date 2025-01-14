<?php

namespace App\BusinessLogic\Models;

readonly class Trip
{

    const float KILOMETERS_FEE = 0.5;
    private string $tripDirection;

    public function __construct(private string $departure,
                                private string $arrival,
                                private float  $distance)
    {
    }

    public static function create(string $departure,
                                  string $arrival,
                                  float  $distance,
                                  bool   $isDepartureInParis,
                                  bool   $isArrivalInParis): Trip
    {
        $trip = new Trip($departure, $arrival, $distance);
        $trip->tripDirection =
            ($isDepartureInParis ? "PARIS" : "OUTSIDE") . " => " . ($isArrivalInParis ? "PARIS" : "OUTSIDE");
        return $trip;
    }

    public function computeKilometersFee(): float
    {
        return $this->distance * self::KILOMETERS_FEE;
    }

    public function getTripDirection(): string
    {
        return $this->tripDirection;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function getDeparture(): string
    {
        return $this->departure;
    }

    public function getArrival(): string
    {
        return $this->arrival;
    }
}