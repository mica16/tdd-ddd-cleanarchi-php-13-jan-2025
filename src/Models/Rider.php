<?php

namespace App\Models;

class Rider
{
    public function __construct(private string $id, private \DateTime $birthDate)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isBirthday(\DateTime $currentDate): bool
    {
        return $this->birthDate->format('m-d') === $currentDate->format('m-d');
    }
}