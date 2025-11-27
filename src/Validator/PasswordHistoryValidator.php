<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PasswordHistoryValidator extends ConstraintValidator
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
       $this->hasher = $hasher;
    }


    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof PasswordHistory) {
            throw new UnexpectedTypeException($constraint, PasswordHistory::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

        }

        $passOk = false;
        foreach ($constraint->user->getPreviousPasswords() as $previousPassword){
            $passOk = $this->hasher->isPasswordValid($previousPassword, $value);
        }

        if (!$passOk){
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
