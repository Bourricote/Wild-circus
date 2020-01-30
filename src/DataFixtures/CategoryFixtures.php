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
            'identifier' => 'voltige',
        ],
        [
            'name' => 'Danse',
            'identifier' => 'danse',
        ],
        [
            'name' => 'Musique',
            'identifier' => 'musique',
        ],
        [
            'name' => 'Animaux',
            'identifier' => 'animals',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::CATEGORIES as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $category->setIdentifier($data['identifier']);

            $manager->persist($category);
            $this->addReference('category_' . $counter, $category);
            $counter++;
        }

        $manager->flush();
    }
}
