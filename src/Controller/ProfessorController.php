<?php

namespace App\Controller;

use App\Entity\ClassLevel;
use App\Entity\Evaluation;
use App\Entity\PreviousPasswords;
use App\Entity\Professor;
use App\Form\ProfessorType;
use App\Repository\ProfessorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prof')]
class ProfessorController extends AbstractController
{
    #[Route('/', name: 'app_professor_index', methods: ['GET'])]
    public function index(ProfessorRepository $professorRepository): Response
    {
        return $this->render('professor/index.html.twig', [
            'professors' => $professorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_professor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $professor = new Professor();
        $form = $this->createForm(ProfessorType::class, $professor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $professor->setRoles(['ROLE_PROFESSOR']);
            $professor->setPassword(
                $userPasswordHasher->hashPassword(
                    $professor,
                    $form->get('password')->getData()
                )
            );

            //encode the plain password for saving it as a previous password
            $previousPassword = new PreviousPasswords($professor);
            $previousPassword->setPassword(
                $userPasswordHasher->hashPassword(
                    $previousPassword,
                    $form->get('password')->getData()
                )
            );
            $professor->addPreviousPasswords($previousPassword);

            $entityManager->persist($professor);
            $entityManager->persist($previousPassword);
            $entityManager->flush();


            return $this->redirectToRoute('app_professor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professor/new.html.twig', [
            'professor' => $professor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_professor_show', methods: ['GET'])]
    public function show(Professor $professor): Response
    {
        return $this->render('professor/show.html.twig', [
            'professor' => $professor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_professor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Professor $professor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfessorType::class, $professor);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_professor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professor/edit.html.twig', [
            'professor' => $professor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_professor_delete', methods: ['POST'])]
    public function delete(Request $request, Professor $professor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$professor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($professor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_professor_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/evaluations', name: 'app_professor_evaluations', methods: ['GET'])]
    public function evaluations(Professor $professor, EntityManagerInterface $entityManager): Response
    {
        $evals = $entityManager->getRepository(Evaluation::class)->findBy(['professor' => $professor]);
        return $this->render('professor/myevals.html.twig', [
            'professor' => $professor,
            'evals' => $evals,
        ]);
    }

    #[Route('/{id}/classes', name: 'app_professor_classes', methods: ['GET'])]
    public function classes(Professor $professor, EntityManagerInterface $entityManager): Response
    {
        $classes = $entityManager->getRepository(ClassLevel::class)->findByProfessor($professor);
        return $this->render('professor/myclasses.html.twig', [
            'professor' => $professor,
            'classes' => $classes->getQuery()->getResult(),
        ]);
    }
}
