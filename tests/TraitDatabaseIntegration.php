<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;

trait TraitDatabaseIntegration {

    public function cleanDatabase(EntityManagerInterface $entityManager): void
    {
        $tables = ['rides'];
        $conn = $entityManager->getConnection();
        foreach ($tables as $table) {
            $sql = 'TRUNCATE TABLE ' . $table . ';';
            try {
                $stmt = $conn->prepare($sql);
                $stmt->executeStatement();
            } catch (\Exception $e) {
                dd($e);
            }
        }
    }
}