<?php

namespace App\Adapters\Secondary\Gateways\Providers\TransactionPerforming;

use App\BusinessLogic\Gateways\Providers\TransactionPerformer;

class PassiveTransactionPerformer implements TransactionPerformer
{
    public function perform(callable $block): mixed
    {
        return $block();
    }
}