<?php
namespace App\DataFixtures;

use App\Entity\Pet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    // php bin/console d:f:l
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i <= 20; $i++) {
            $pet = new Pet();
            $pet->setName($faker->firstName());
            $pet->setSpecies($faker->numberBetween(1, 5));
            $pet->setRace($faker->numberBetween(1, 50));
            $pet->setBirthDate(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 years', 'now')));
            $pet->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 year', 'now')));
            $pet->setOwnerId($faker->optional()->numberBetween(1, 100));

            $manager->persist($pet);
        }
        $manager->flush();
    }
}
