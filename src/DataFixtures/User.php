<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class User extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $car = new Car();
        // $manager->persist($car);

        $manager->flush();
    }
}
