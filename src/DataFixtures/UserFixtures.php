<?php

namespace App\DataFixtures;

use App\Entity\ClassLevel;
use App\Entity\Professor;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        //create several users for tests
        $faker = Factory::create();

        for ($i = 1; $i <= 50; $i++) {
            $user = new Student();
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $user->setEmail(sprintf(strtolower($firstname.'.'.$lastname).'@lycee-faure.fr', $i));
            $user->setName($firstname.' '.$lastname);
            $plain = 'etudiant';
            $user->setPassword($this->hasher->hashPassword($user, $plain));
            $user->setRoles(['ROLE_STUDENT']);
            //add ClassLevel reference
            $classLevelRef = 'class_level_' . $faker->numberBetween(1, 5);
            $user->setClassLevel($this->getReference($classLevelRef, ClassLevel::class));
            $manager->persist($user);

            $this->addReference('student_' . $i, $user);
        }

        for ($i = 1; $i <= 5; $i++) {
            $user = new Professor();
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $user->setEmail(sprintf(strtolower($firstname.'.'.$lastname).'@lycee-faure.fr', $i));
            $user->setName($firstname.' '.$lastname);            $plain = 'prof';
            $user->setPassword($this->hasher->hashPassword($user, $plain));
            $user->setRoles(['ROLE_PROFESSOR']);
            for ($j = 1; $j <= 2; $j++) {
                $subjectRef = 'subject_' . $faker->numberBetween(1, 7);
                $user->addSubject($this->getReference($subjectRef, Subject::class));
            }
            for ($j = 1; $j <= 3; $j++) {
                $classLevelRef = 'class_level_' . $faker->numberBetween(1, 5);
                $user->addClassLevel($this->getReference($classLevelRef, ClassLevel::class));
            }

            $manager->persist($user);

            $this->addReference('professor_' . $i, $user);
        }

        // Optionnel: un admin
        $admin = new User();
        $admin->setEmail('admin@lycee-faure.fr');
        $admin->setPassword($this->hasher->hashPassword($admin, 'adminpassword'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClassLevelFixtures::class,
            SubjectFixtures::class,
        ];
    }
}
