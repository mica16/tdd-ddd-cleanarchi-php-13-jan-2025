<?php

namespace App\BusinessLogic\UseCases\RideBooking;

class BookRideCommand
{

    public function __construct(public string $departure, public string $arrival, public bool $wantsUberX)
    {
    }
}