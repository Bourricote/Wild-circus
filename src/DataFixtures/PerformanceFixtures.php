<?php

namespace App\DataFixtures;

use App\Entity\Performance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PerformanceFixtures extends Fixture
{
    const PERFORMANCES = [
        [
            'name' => 'Acrobaties',
            'text' => 'avec des acrobates',

        ],
        [
            'name' => 'Motos partout',
            'text' => 'même dans une roue',
        ],
        [
            'name' => 'Affreux clowns',
            'text' => 'tristes et joyeux',
        ],
        [
            'name' => 'Tigres enragés',
            'text' => 'et vieux',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $counter = 0;
        foreach (self::PERFORMANCES as $data) {
            $performance = new Performance();
            $performance->setName($data['name']);
            $performance->setText($data['text']);
            $performance->setCategory($this->getReference('category_' . $counter));
            $manager->persist($performance);

            $counter++;
        }

        $manager->flush();
    }
}
