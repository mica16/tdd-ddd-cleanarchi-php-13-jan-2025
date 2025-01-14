<?php

namespace App\Adapters\Primary\Symfony\Controllers;


use App\BusinessLogic\UseCases\RideBooking\BookRideCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rides')]
class RideController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('', name: 'book_ride', methods: ['POST'])]
    public function bookRide(Request $request): JsonResponse
    {
        $postBody = $request->getPayload()->all();
        $this->messageBus->dispatch(new BookRideCommand(
            $postBody['departure'],
            $postBody['arrival'],
            $postBody['uberX'],
        ));
        return $this->json("Ride booked", 201);
    }
}