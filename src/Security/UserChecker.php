<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user): void
    {
        // TODO: Implement checkPreAuth() method.
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
        if ($user->getPasswordChangeDate()->diff(new \DateTime('now'))->days > 90){
            throw new CustomUserMessageAccountStatusException('Mot de passe modifié il y a plus de 90 jours. Vous devez le remplacer. Cliquez sur Mot de passe oublié.', [], 1);
        }
    }
}
