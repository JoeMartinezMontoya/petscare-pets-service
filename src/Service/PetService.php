<?php
namespace App\Service;

use App\Entity\Pet;
use App\Repository\PetRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PetService
{
    private PetRepository $petRepository;
    private SerializerInterface $serializer;
    private CacheItemPoolInterface $cache;

    public function __construct(PetRepository $petRepository, SerializerInterface $serializer, CacheItemPoolInterface $cache)
    {
        $this->petRepository = $petRepository;
        $this->serializer    = $serializer;
        $this->cache         = $cache;
    }

    public function createPet(array $data): ?string
    {
        $birthdate = \DateTimeImmutable::createFromFormat('Y-m-d', $data['birthDate']);
        if (! $birthdate) {
            throw new \InvalidArgumentException('Birthdate format is not valid.');
        }

        $pet = (new Pet)
            ->setName($data['name'])
            ->setSpecies($data['species'])
            ->setRace($data['race'])
            ->setBirthDate($birthdate)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setOwnerId($data['user_id'] ?? null);

        $this->petRepository->persistPet($pet);

        if ($pet->getOwnerId()) {
            $this->cache->deleteItem("user_pets_" . $pet->getOwnerId());
        }

        return $pet->getName() ?? null;
    }

    public function getLastPets(int $limit): string
    {
        $cacheKey  = "last_pets_$limit";
        $cacheItem = $this->cache->getItem($cacheKey);

        if (! $cacheItem->isHit()) {
            $lastPets       = $this->petRepository->findLastPets($limit);
            $serializedPets = $this->serializer->serialize($lastPets, 'json');

            $cacheItem->set($serializedPets);
            $cacheItem->expiresAfter(3600);
            $this->cache->save($cacheItem);
        }

        return $cacheItem->get();
    }

    public function getUserPets(int $userId): mixed
    {
        $cacheKey  = "user_pets_$userId";
        $cacheItem = $this->cache->getItem($cacheKey);
        if (! $cacheItem->isHit()) {
            $userPets = $this->serializer->serialize($this->petRepository->findBy(['ownerId' => $userId]), 'json');
            $cacheItem->set($userPets);
            $cacheItem->expiresAfter(86400);
            $this->cache->save($cacheItem);
        }
        return $cacheItem->get();
    }
}
