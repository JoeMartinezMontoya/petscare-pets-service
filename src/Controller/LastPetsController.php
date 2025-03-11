<?php
namespace App\Controller;

use App\Exception\ApiException;
use App\Service\PetService;
use App\Utils\ApiResponse;
use App\Utils\HttpStatusCodes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LastPetsController extends AbstractController
{
    #[Route('/api/pets/last-pets', name: 'last_pets', methods: ['GET'])]
    public function __invoke(PetService $petService): JsonResponse
    {
        try {
            $lastPets = $petService->getLastPets(5);
            return ApiResponse::success([
                "detail"    => "Last pets",
                "message"   => "Last pets retrieved successfully",
                "last-pets" => $lastPets,
            ], HttpStatusCodes::SUCCESS);
        } catch (\Exception $e) {
            if ($e instanceof ApiException) {
                return ApiResponse::error([
                    "title"   => $e->getTitle(),
                    "detail"  => $e->getDetail(),
                    "message" => $e->getMessage(),
                ], $e->getStatusCode());
            }

            return ApiResponse::error([
                "title"   => "Unexpected Error",
                "detail"  => "An unexpected error occurred while creating the user",
                "message" => $e->getMessage(),
            ], HttpStatusCodes::SERVER_ERROR);
        }
    }

}
