<?php

namespace App\DataFixtures;

use App\Entity\ClassLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClassLevelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $bts = ['SIO', 'NDRC', 'MSPR', 'CG', 'SAM'];

        for ($i = 1; $i <= 5; $i++) {
            $classLevel = new ClassLevel();
            $classLevel->setLabel(sprintf('%s%d', $faker->randomElement($bts), $i));
            $manager->persist($classLevel);

            $this->addReference('class_level_' . $i, $classLevel);
        }

        $manager->flush();
    }
}
