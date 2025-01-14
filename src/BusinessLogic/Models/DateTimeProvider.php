<?php

namespace App\BusinessLogic\Models;

interface DateTimeProvider
{
    public function now(): \DateTime;
}