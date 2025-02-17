<?php
namespace App\Service;

use App\Entity\Pet;
use App\Repository\PetRepository;
use Symfony\Component\HttpFoundation\Response;

class PetService
{
    private PetRepository $petRepository;

    public function __construct(PetRepository $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    public function createPet(array $data): array
    {
        $pet       = new Pet();
        $birthdate = \DateTimeImmutable::createFromFormat('Y-m-d', $data['birthDate']);
        if (! $birthdate) {
            throw new \InvalidArgumentException('La date de naissance fournie est invalide.');
        }
        $pet->setName($data['name'])
            ->setSpecies($data['species'])
            ->setRace($data['race'])
            ->setBirthDate($birthdate)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setOwnerId($data['user_id'] ?? null);

        $this->petRepository->persistPet($pet);

        return [
            "title"   => "Le nouvel animal a été enregistré",
            "status"  => Response::HTTP_OK,
            "detail"  => "",
            "message" => "Tudu bon",
        ];
    }
}
