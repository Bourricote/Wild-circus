<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        // Admin
        $user = new User();
        $user->setFirstname('Anne');
        $user->setLastname('Bourricote');
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@wild-circus.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $this->addReference('admin', $user);

        // Fan
        $user = new User();
        $user->setFirstname('Gregory');
        $user->setLastname('Beaudet');
        $user->setEmail(strtolower($user->getFirstname() . '.' . $user->getLastname() . '@orange.fr'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRoles(['ROLE_FAN']);
        $manager->persist($user);
        $this->addReference('fan', $user);

        $manager->flush();
    }
}
