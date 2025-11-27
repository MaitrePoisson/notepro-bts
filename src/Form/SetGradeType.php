<?php

namespace App\Form;

use App\Entity\Student;
use App\Repository\GradeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetGradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $eval = $options['evaluation'];

        foreach ($options['students'] as $student) {
            $grade = null;
            $presence = false;
            $gradeObj = $student->getGradeByEval($eval);
            if ($gradeObj != null){
                $grade = $gradeObj->getGrade();
                $presence = !$gradeObj->isPresent();
            }

            $builder
                ->add('grade_'.$student->getId(), NumberType::class, [
                    'label' => $student->getName(),
                    'attr' => ['id' => $student->getId()],
                    'data' => $grade,
                ])
                ->add('presence_'.$student->getId(), CheckboxType::class, [
                    'label' => 'Absent',
                    'attr' => ['checked' => $presence],
                    'required' => false,
                ]);
        }

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Ok'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'students' => [],
            'evaluation' => null,
        ]);
    }
}
