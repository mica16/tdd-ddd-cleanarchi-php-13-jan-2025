<?php

namespace App\Tests\EndToEnd\Adapters\Primary\Symfony\Controllers;

use App\Adapters\Secondary\Gateways\Repositories\Doctrine\Entities\DoctrineRideEntity;
use App\BusinessLogic\Gateways\Providers\TripScanner;
use App\BusinessLogic\Gateways\Repositories\RideRepository;
use App\BusinessLogic\Gateways\Repositories\RiderRepository;
use App\BusinessLogic\Models\DateTimeProvider;
use App\BusinessLogic\Models\Ride;
use App\BusinessLogic\Models\Rider;
use App\Tests\TraitDatabaseIntegration;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RideControllerEndToEndTest extends WebTestCase
{

    use TraitDatabaseIntegration;

    private KernelBrowser $client;
    private ContainerInterface $container;
    private ?RiderRepository $riderRepository;
    private ?RideRepository $rideRepository;
    private ?TripScanner $tripScanner;
    private ?DateTimeProvider $dateTimeProvider;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->riderRepository = $this->container->get(RiderRepository::class);
        $this->tripScanner = $this->container->get(TripScanner::class);
        $this->dateTimeProvider = $this->container->get(DateTimeProvider::class);
        $this->rideRepository = $this->container->get(RideRepository::class);
        $this->entityManager = $this->container->get('doctrine')->getManager();
        self::cleanDatabase($this->entityManager);
    }

    #[Test]
    public function should_be_offered_a_uber_x_ride_in_case_of_long_trip_and_birthday()
    {
        $this->tripScanner->distance = 100;
        $this->riderRepository->feedWith(new Rider('456def', new \DateTime('1990-02-03')));
        $this->dateTimeProvider->currentDate = new \DateTime('1990-02-03');
        $this->client->request('POST', '/api/rides', [
            'rideId' => '123abc',
            'departure' => '8 avenue Foch PARIS',
            'arrival' => '111 avenue Victor Hugo Aubervilliers',
            'uberX' => true
        ]);
        $this->assertResponseStatusCodeSame(201);

        $savedDoctrineRideEntity = $this->entityManager->find(DoctrineRideEntity::class, '123abc');
        $this->assertEquals(new DoctrineRideEntity(
            '123abc',
            "8 avenue Foch PARIS",
            "111 avenue Victor Hugo Aubervilliers",
            true,
            70,
        ), $savedDoctrineRideEntity);
    }
}
