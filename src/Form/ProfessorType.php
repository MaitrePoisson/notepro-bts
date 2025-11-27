<?php

namespace App\Form;

use App\Entity\Professor;
use App\Entity\Subject;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
            ])
            ->add('subjects', EntityType::class, [
                'class' => Subject::class,
                'label' => 'Matière',
                'choice_label' => 'label',
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.label', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professor::class,
        ]);
    }
}
