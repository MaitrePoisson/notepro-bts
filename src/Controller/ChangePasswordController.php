<?php

namespace App\Controller;

use App\Entity\PreviousPasswords;
use App\Form\ChangePasswordFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ChangePasswordController extends AbstractController
{
    #[Route('/changepassword', name: 'user_change_password', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function changePassword(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);
        $success = false;
        if ($form->isSubmitted() && $form->isValid()) {
            //check if the password set is different from the last 5 previous passwords
            $passOk = false;
            $nbPreviousPasswords = $user->getPreviousPasswords()->count();

            for ($i = $nbPreviousPasswords - 1; $i >= $nbPreviousPasswords - 5 && $i >= 0; $i--){
                if($passwordHasher->isPasswordValid($user->getPreviousPasswords()[$i], $form->get('plainPassword')->getData())){
                    $passOk = true;
                }
            }

            if ($passOk){
                $this->addFlash('reset_password_error', 'Vous ne pouvez pas réutiliser l\'un de vos 5 derniers mots de passe');
            } else {
                // Encode(hash) the plain password, and set it.
                $encodedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                );
                //encode the plain password for saving it as a previous password
                $previousPassword = new PreviousPasswords($user);
                $previousPassword->setPassword(
                    $passwordHasher->hashPassword(
                        $previousPassword,
                        $form->get('plainPassword')->getData()
                    )
                );
                $user->addPreviousPasswords($previousPassword);

                $user->setPassword($encodedPassword);

                $doctrine->getManager()->persist($user);
                $doctrine->getManager()->persist($previousPassword);
                $doctrine->getManager()->flush();

                $success = true;
            }
        }

        return $this->render('change_password/index.html.twig', [
            'user' => $user,
            'form' => $form,
            'success' => $success,
        ]);
    }
}
