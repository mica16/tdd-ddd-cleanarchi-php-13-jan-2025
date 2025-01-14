<?php

namespace App\Tests\Integration\Adapters\Secondary\Gateways\Providers\TripScanning;

use App\BusinessLogic\Gateways\Providers\TripScanner;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GoogleTripScannerIntegTest extends KernelTestCase
{

    private ?TripScanner $tripScanner;

    public function setUp(): void
    {
        parent::setUp();
        $bootedKernel = $this::bootKernel(["environment" => "test"]);
        $container = $bootedKernel->getContainer();
        $this->tripScanner = $container->get(TripScanner::class);
    }

    #[Test]
    public function can_determine_distance_between_two_addresses()
    {
        $distance = $this->tripScanner->distanceBetween(
            '8 avenue Foch Paris',
            '111 avenue Victor Hugo Aubervilliers');
        $this->assertEqualsWithDelta(10.772, $distance, '1.0');
    }

    #[Test]
    public function can_determine_whether_an_address_is_in_Paris()
    {
        $this->assertTrue($this->tripScanner->isAddressInParis('8 avenue Foch Paris'));
        $this->assertFalse($this->tripScanner->isAddressInParis('111 avenue Victor Hugo Aubervilliers'));
    }


}