<?php

namespace App\DataFixtures;

use App\Entity\Grade;
use App\Entity\Evaluation;
use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GradeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $evaluations = $manager->getRepository(Evaluation::class)->findAll();

        $counter = 0;
        foreach ($evaluations as $eval) {
            $evalClassLevel = $eval->getClassLevel();
            if (!$evalClassLevel) {
                continue;
            }

            foreach ($eval->getClassLevel()->getStudents() as $student) {
                $grade = new Grade();
                $grade->setEvaluation($eval);
                $grade->setStudent($student);
                // probabilité de créer une note pour cet étudiant (80%)
                if (!$faker->boolean(80)) {
                    $grade->setPresent(false);
                } else {
                    $gradeValue = $faker->numberBetween(0, $eval->getBareme());
                    $grade->setGrade($gradeValue);
                    $grade->setPresent(true);
                }

                $manager->persist($grade);

                $this->addReference('grade_' . $counter++, $grade);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClassLevelFixtures::class,
            SubjectFixtures::class,
            UserFixtures::class,
            EvaluationFixtures::class,
        ];
    }
}
