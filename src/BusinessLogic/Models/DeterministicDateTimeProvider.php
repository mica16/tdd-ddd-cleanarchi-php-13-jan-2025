<?php

namespace App\BusinessLogic\Models;

class DeterministicDateTimeProvider implements DateTimeProvider
{
    public  \DateTime $currentDate;

    public function now(): \DateTime
    {
        return $this->currentDate;
    }
}