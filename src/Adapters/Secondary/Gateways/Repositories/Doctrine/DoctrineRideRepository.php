<?php

namespace App\Adapters\Secondary\Gateways\Repositories\Doctrine;

use App\Adapters\Secondary\Gateways\Repositories\Doctrine\Entities\DoctrineRideEntity;
use App\BusinessLogic\Gateways\Repositories\RideRepository;
use App\BusinessLogic\Models\Ride;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineRideRepository implements RideRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    function save(Ride $ride): void
    {
        $doctrineRideEntity = new DoctrineRideEntity(
            $ride->getId(),
            $ride->getDeparture(),
            $ride->getArrival(),
            $ride->isUberX(),
            $ride->getPrice()
        );
        $this->entityManager->persist($doctrineRideEntity);
        $this->entityManager->flush();
    }
}