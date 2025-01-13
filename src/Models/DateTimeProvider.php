<?php

namespace App\Models;

interface DateTimeProvider
{
    public function now(): \DateTime;
}