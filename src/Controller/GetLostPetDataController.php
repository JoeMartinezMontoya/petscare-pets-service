<?php
namespace App\Controller;

use App\Service\PetService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetLostPetDataController extends AbstractController
{

    #[Route('/public/api/lost-pet/{id}', name: 'lost_pet', methods: ['GET'])]
    public function __invoke(int $id, PetService $service): JsonResponse
    {
        $response = $service->getLostPetData($id);
        return ApiResponse::success(['lostPetData' => $response]);
    }
}
