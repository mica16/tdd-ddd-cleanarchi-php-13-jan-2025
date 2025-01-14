<?php

namespace App\Adapters\Primary\Symfony\EventListeners;

use App\BusinessLogic\Gateways\Providers\TripScanner;
use App\BusinessLogic\Gateways\Repositories\RiderRepository;
use App\BusinessLogic\Models\DateTimeProvider;
use App\BusinessLogic\Models\Rider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AppStartupListener
{

    private RiderRepository $riderRepository;
    private TripScanner $tripScanner;
    private DateTimeProvider $dateTimeProvider;

    public function __construct(RiderRepository  $riderRepository,
                                TripScanner      $tripScanner,
                                DateTimeProvider $dateTimeProvider)
    {
        $this->riderRepository = $riderRepository;
        $this->tripScanner = $tripScanner;
        $this->dateTimeProvider = $dateTimeProvider;
    }

    #[AsEventListener(event: 'kernel.request', priority: 255)]
    public function onKernelRequest(RequestEvent $event)
    {
        $this->riderRepository->feedWith(new Rider('456def', new \DateTime('1990-02-05')));
        $this->tripScanner->distance = 100;
        $this->dateTimeProvider->currentDate = new \DateTime('1990-02-05');
    }
}