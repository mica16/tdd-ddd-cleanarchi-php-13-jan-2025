<?php

namespace App\Adapters\Primary\Symfony\Controllers;


use App\BusinessLogic\UseCases\RideBooking\BookRideUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rides')]
class RideController extends AbstractController
{
    public function __construct(private readonly BookRideUseCase $bookRideUseCase)
    {
    }

    #[Route('', name: 'book_ride', methods: ['POST'])]
    public function bookRide(Request $request): JsonResponse
    {
        $postBody = $request->getPayload()->all();
        $this->bookRideUseCase->execute(
            $postBody['departure'],
            $postBody['arrival'],
            $postBody['uberX'],
        );
        return $this->json("Ride booked", 201);
    }
}