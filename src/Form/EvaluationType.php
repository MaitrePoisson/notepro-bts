<?php

namespace App\Form;

use App\Entity\ClassLevel;
use App\Entity\Evaluation;
use App\Entity\Subject;
use App\Repository\ClassLevelRepository;
use App\Repository\SubjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $eval = $options['data'];
        $prof = $eval->getProfessor();

        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'évaluation',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('datePublish', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de publication des notes',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('label', TextType::class, [
                'label' => 'Titre de l\'évaluation',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: Devoir surveillé n°1'],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('bareme', IntegerType::class, [
                'label' => 'Barème',
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                    'class' => 'form-control'
                ],
                'row_attr' => ['class' => 'mb-3'],
            ])
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'label' => 'Matière',
                'choice_label' => 'label',
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select'],
                'row_attr' => ['class' => 'mb-3'],
                'query_builder' => function(SubjectRepository $er) use($prof){
                    return $er->findByProfessor($prof);
                },
            ])
            ->add('classLevel', EntityType::class, [
                'class' => ClassLevel::class,
                'label' => 'Classe',
                'choice_label' => 'label',
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select'],
                'row_attr' => ['class' => 'mb-3'],
                'query_builder' => function(ClassLevelRepository $er) use($prof){
                    return $er->findByProfessor($prof);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
