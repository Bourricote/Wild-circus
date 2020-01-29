<?php

namespace App\DataFixtures;

use App\Entity\Tour;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class TourFixtures extends Fixture
{
    const TOURS = [
        [
            'city' => 'Cherbourg',
            'price' => '12'
        ],
        [
            'city' => 'Bordeaux',
            'price' => '20'
        ],
        [
            'city' => 'Vauville',
            'price' => '7'
        ],
    ];

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        foreach (self::TOURS as $data) {
            $tour = new Tour();
            $tour->setCity($data['city']);
            $tour->setDate($faker->dateTimeThisYear);
            $tour->setPrice($data['price']);

            $manager->persist($tour);
        }

        $manager->flush();
    }
}
