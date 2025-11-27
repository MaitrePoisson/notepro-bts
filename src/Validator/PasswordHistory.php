<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PasswordHistory extends Constraint
{
    public string $message = 'Vous ne pouvez pas réutiliser l\'un de vos 5 derniers mots de passe';

    #[HasNamedArguments]
    public function __construct(
        public User $user,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([], $groups, $payload);
    }
}
