<?php
namespace App\Controller;

use App\Service\PetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PetCreationController extends AbstractController
{
    private PetService $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    #[Route('/api/pets/create-pet', name: 'create_pet', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data   = json_decode($request->getContent(), true);
        $result = $this->petService->createPet($data);

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
