<?php

namespace App\Adapters\Secondary\Gateways\Providers\TransactionPerforming;

use App\BusinessLogic\Gateways\Providers\TransactionPerformer;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTransactionPerformer implements TransactionPerformer {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function perform(callable $block): mixed
    {
        return $this->entityManager->wrapInTransaction($block);
    }
}