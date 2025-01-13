<?php

namespace App\Models;

class Ride {

        public function __construct(private string $id,
                                    private string $departure,
                                    private string $arrival,
                                    private float    $price)
        {
        }
}