<?php

namespace App\BusinessLogic\Gateways\Providers;

interface TransactionPerformer {
    public function perform(callable $block): mixed;
}