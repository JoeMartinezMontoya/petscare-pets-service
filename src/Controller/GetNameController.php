<?php
namespace App\Controller;

use App\Service\PetService;
use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GetNameController extends AbstractController
{
    #[Route('/public/api/pets/{id}/name', name: 'get_name', methods: ['GET'])]
    public function __invoke(int $id, PetService $service)
    {
        $name = $service->getName($id);
        return ApiResponse::success(['pet_name' => $name]);
    }
}
