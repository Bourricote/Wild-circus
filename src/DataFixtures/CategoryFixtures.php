<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        [
            'name' => 'Voltige',
        ],
        [
            'name' => 'Danse',
        ],
        [
            'name' => 'Musique',
        ],
        [
            'name' => 'Animaux',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::CATEGORIES as $data) {
            $category = new Category();
            $category->setName($data['name']);

            $manager->persist($category);
            $this->addReference('category_' . $counter, $category);
            $counter++;
        }

        $manager->flush();
    }
}
