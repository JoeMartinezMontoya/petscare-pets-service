<?php
namespace App\Controller;

use App\Exception\ApiException;
use App\Service\PetService;
use App\Utils\ApiResponse;
use App\Utils\HttpStatusCodes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetUserPetsController extends AbstractController
{
    #[Route('/private/api/pets/user/{id}', name: 'get_user_pet', methods: ['GET'])]
    public function __invoke(PetService $petService, int $id): JsonResponse
    {
        #TODO : Rework with Token email claim
        try {
            $pets = $petService->getUserPets($id);
            return ApiResponse::success([
                "user-pets" => $pets,
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
