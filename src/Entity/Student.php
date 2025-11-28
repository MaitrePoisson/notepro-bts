<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{
    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Grade::class, orphanRemoval: true)]
    private Collection $grades;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ClassLevel $classLevel = null;

    public function __construct()
    {
        parent::__construct();
        $this->grades = new ArrayCollection();
    }

    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setStudent($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            if ($grade->getStudent() === $this) {
                $grade->setStudent(null);
            }
        }

        return $this;
    }

    public function getClassLevel(): ?ClassLevel
    {
        return $this->classLevel;
    }

    public function setClassLevel(?ClassLevel $classLevel): static
    {
        $this->classLevel = $classLevel;

        return $this;
    }

    public function getGradeByEval (Evaluation $evaluation): ?Grade
    {
        foreach ($this->getGrades() as $grade){
            if ($grade->getEvaluation() === $evaluation){
                return $grade;
            }
        }
        return null;
    }

    public function getGradesBySubject(): array
    {
        $gradesBySubject = [];
        $now = new \DateTime();

        foreach ($this->getGrades() as $grade) {
            $evaluation = $grade->getEvaluation();

            if ($evaluation->getDatePublish() && $evaluation->getDatePublish() <= $now) {
                $subjectLabel = $evaluation->getSubject()->getLabel();
                $gradesBySubject[$subjectLabel][] = $grade;
            }
        }

        return $gradesBySubject;
    }

    public function getAveragesBySubject(): array
    {
        $averages = [];
        $gradesBySubject = $this->getGradesBySubject();

        foreach ($gradesBySubject as $subject => $grades) {
            $totalPoints = 0;
            $totalBareme = 0;

            foreach ($grades as $grade) {
                if ($grade->isPresent() && $grade->getGrade() !== null) {
                    $totalPoints += $grade->getGrade();
                    $totalBareme += $grade->getEvaluation()->getBareme();
                }
            }

            if ($totalBareme > 0) {
                $averages[$subject] = round(($totalPoints / $totalBareme) * 20, 2);
            } else {
                $averages[$subject] = null;
            }
        }

        return $averages;
    }

    public function getAdvicesBySubject(array $averages): array
    {
        $advices = [];

        foreach ($averages as $subject => $average) {
            if ($average !== null && $average < 10) {
                $advices[$subject] = $this->generateAdviceForSubject($subject);
            }
        }

        return $advices;
    }

    private function generateAdviceForSubject(string $subject): string
    {
        $subjectLower = mb_strtolower($subject);

        if (str_contains($subjectLower, 'math')) {
            return "Revois les exercices de base et n'hésite pas à refaire les démonstrations du cours.";
        }
        if (str_contains($subjectLower, 'anglais') || str_contains($subjectLower, 'english')) {
            return "Essaie de regarder des séries en VOST et de lire des articles de presse anglophone.";
        }
        if (str_contains($subjectLower, 'français') || str_contains($subjectLower, 'culture')) {
            return "Soigne ton orthographe et structure davantage tes arguments lors des rédactions.";
        }
        if (str_contains($subjectLower, 'bloc 1') || str_contains($subjectLower, 'support')) {
            return "Entraîne-toi sur des machines virtuelles pour mieux maîtriser les commandes système.";
        }
        if (str_contains($subjectLower, 'bloc 2') || str_contains($subjectLower, 'dév')) {
            return "Pratique le code régulièrement (Kata, petits projets) pour acquérir les automatismes.";
        }
        if (str_contains($subjectLower, 'cyber')) {
            return "Revois les concepts fondamentaux du modèle OSI et les protocoles de sécurité.";
        }
        if (str_contains($subjectLower, 'cejm')) {
            return "Fais des fiches de synthèse sur les concepts juridiques et économiques clés.";
        }

        return "Prends rendez-vous avec le professeur pour identifier les points de blocage spécifiques.";
    }
}
