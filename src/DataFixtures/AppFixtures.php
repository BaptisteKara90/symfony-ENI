<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        for($i = 0; $i < 50; $i++) {
           $serie = new Serie();
           $serie->setName($faker->word())
               ->setStatus($faker->randomElement(['ended', 'returning', 'cancelled']))
               ->setPoster($faker->imageUrl())
               ->setTmdbId($faker->randomDigit())
               ->setPopularity($faker->numberBetween(0, 1000))
               ->setFirstAirDate($faker->dateTimeBetween("-2 years", "-6 months"))
               ->setDateCreated(new \datetime())
               ->setBackdrop($faker->imageUrl())
               ->setGenres($faker->randomElement(["fantasy", "action", "comedy", "drama", "fantasy"]))
               ->setVote($faker->numberBetween(0, 10));

           $manager->persist($serie);
        }
        $manager->flush();

    }
}
