<?php

namespace App\Controller;

use App\Entity\ClassLevel;
use App\Entity\Evaluation;
use App\Entity\Grade;
use App\Entity\Professor;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InitController extends AbstractController
{
    #[Route('/init', name: 'app_init')]
    public function index(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        //création des données de test
        //Administrateur
        $admin = new User();
        $admin->setName('administrateur');
        $admin->setEmail('admin@lycee-faure.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($hasher->hashPassword($admin, '&6HAUTdanslaFauré'));
        //Classes
        $class1 = new ClassLevel();
        $class1->setLabel('SIO1');
        $class2 = new ClassLevel();
        $class2->setLabel('SIO2');
        //Matieres
        $subject1 = new Subject();
        $subject1->setLabel('Bloc 1-Support et mise à disposition de services informatiques');
        $subject2 = new Subject();
        $subject2->setLabel('Bloc 2-Conception et développement d\'applications');
        $subject3 = new Subject();
        $subject3->setLabel('Bloc 3-Cybersécurité');
        $subject4 = new Subject();
        $subject4->setLabel('Ateliers de professionnalisation');
        //Prof
        $prof = new Professor();
        $prof->setName('Etienne Buffet');
        $prof->setEmail('etienne.buffet@lycee-faure.fr');
        $prof->setRoles(['ROLE_PROFESSOR']);
        $prof->setPassword($hasher->hashPassword($prof, 'prof'));
        $prof->addSubject($subject1);
        $prof->addSubject($subject2);
        $prof->addSubject($subject3);
        $prof->addSubject($subject4);
        $prof->addClassLevel($class1);
        $prof->addClassLevel($class2);
        $prof2 = new Professor();
        $prof2->setName('David Tissot');
        $prof2->setEmail('david.tissot@lycee-faure.fr');
        $prof2->setRoles(['ROLE_PROFESSOR']);
        $prof2->setPassword($hasher->hashPassword($prof, 'prof'));
        $prof2->addSubject($subject1);
        $prof2->addSubject($subject2);
        $prof2->addSubject($subject3);
        $prof2->addSubject($subject4);
        $prof2->addClassLevel($class1);
        $prof2->addClassLevel($class2);

        //Etudiants
        $etudiant = new Student();
        $etudiant->setName('John Lenon');
        $etudiant->setEmail('john.lenon@lycee-faure.fr');
        $etudiant->setClassLevel($class1);
        $etudiant->setRoles(['ROLE_STUDENT']);
        $etudiant->setPassword($hasher->hashPassword($etudiant, 'etudiant'));
        $etudiant2 = new Student();
        $etudiant2->setName('Paul Mc Cartney');
        $etudiant2->setEmail('paul.mccartney@lycee-faure.fr');
        $etudiant2->setClassLevel($class1);
        $etudiant2->setRoles(['ROLE_STUDENT']);
        $etudiant2->setPassword($hasher->hashPassword($etudiant2, 'etudiant'));

        $eval = new Evaluation();
        $eval->setProfessor($prof);
        $eval->setClassLevel($class1);
        $eval->setDate(new \DateTime('now'));
        $eval->setSubject($subject1);
        $eval->setBareme(20);
        $eval->setLabel('Eval sur Symfony');

        $eval2 = new Evaluation();
        $eval2->setProfessor($prof2);
        $eval2->setClassLevel($class1);
        $eval2->setDate(new \DateTime('now'));
        $eval2->setSubject($subject1);
        $eval2->setBareme(20);
        $eval2->setLabel('Eval sur Java');

        $noteEtudiant1 = new Grade();
        $noteEtudiant1->setEvaluation($eval);
        $noteEtudiant1->setGrade(17);
        $noteEtudiant1->setStudent($etudiant);
        $noteEtudiant2 = new Grade();
        $noteEtudiant2->setEvaluation($eval);
        $noteEtudiant2->setGrade(13);
        $noteEtudiant2->setStudent($etudiant2);

        $noteEtudiant3 = new Grade();
        $noteEtudiant3->setEvaluation($eval2);
        $noteEtudiant3->setGrade(8);
        $noteEtudiant3->setStudent($etudiant);
        $noteEtudiant4 = new Grade();
        $noteEtudiant4->setEvaluation($eval2);
        $noteEtudiant4->setGrade(5);
        $noteEtudiant4->setStudent($etudiant2);

        //persistence des données
        $entityManager->persist($admin);
        $entityManager->persist($class1);
        $entityManager->persist($class2);
        $entityManager->persist($subject1);
        $entityManager->persist($subject2);
        $entityManager->persist($subject3);
        $entityManager->persist($subject4);
        $entityManager->persist($prof);
        $entityManager->persist($prof2);
        $entityManager->persist($etudiant);
        $entityManager->persist($etudiant2);
        $entityManager->persist($eval);
        $entityManager->persist($eval2);
        $entityManager->persist($noteEtudiant1);
        $entityManager->persist($noteEtudiant2);
        $entityManager->persist($noteEtudiant3);
        $entityManager->persist($noteEtudiant4);

        $entityManager->flush();

        return $this->render('init/index.html.twig', [
            'message' => 'Initialisation OK-jetez un oeil au contenu de la bdd',
        ]);
    }
}
