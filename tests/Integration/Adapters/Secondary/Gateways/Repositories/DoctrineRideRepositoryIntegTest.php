<?php

namespace App\Tests\Integration\Adapters\Secondary\Gateways\Repositories;

use App\Adapters\Secondary\Gateways\Repositories\Doctrine\Entities\DoctrineRideEntity;
use App\BusinessLogic\Gateways\Repositories\RideRepository;
use App\BusinessLogic\Models\Ride;
use App\Tests\TraitDatabaseIntegration;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineRideRepositoryIntegTest extends KernelTestCase
{

    use TraitDatabaseIntegration;

    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;

    function setUp(): void
    {
        parent::setUp();
        $bootedKernel = $this::bootKernel(["environment" => "test"]);
        $this->container = $bootedKernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
        self::cleanDatabase($this->entityManager);
    }

    #[Test]
    public function can_save_a_ride(): void
    {
        // GIVEN
        $rideRepository = $this->container->get(RideRepository::class);
        $ride = new Ride(
            '71efde49-0a02-4ede-9cd2-c8f773fd6baf',
            '8 avenue Foch Paris',
            '111 avenue Victor Hugo Aubervilliers',
            true,
            99
        );
        // WHEN
        $rideRepository->save($ride);

        $savedDoctrineRideEntity = $this->entityManager->find(DoctrineRideEntity::class, '71efde49-0a02-4ede-9cd2-c8f773fd6baf');

        // THEN
        $this->assertEquals(new DoctrineRideEntity(
            '71efde49-0a02-4ede-9cd2-c8f773fd6baf',
            '8 avenue Foch Paris',
            '111 avenue Victor Hugo Aubervilliers',
            true,
            99
        ), $savedDoctrineRideEntity);
    }

}