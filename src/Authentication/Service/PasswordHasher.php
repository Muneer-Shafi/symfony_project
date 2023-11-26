<?php
declare(strict_types=1);

namespace App\Authentication\Service;


use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function getEncodedPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
    }
}