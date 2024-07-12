<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@gmail.com');
        $user->setPassword('test');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUsername('test');


        $manager->persist($user);

        $manager->flush();
    }
}
