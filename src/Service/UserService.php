<?php

namespace App\Service;

use App\Model\RoleEnum;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
	public function __construct(
		private EntityManagerInterface $manager,
		private UserPasswordHasherInterface $passwordHasher,
	) {
	}

	public function create(User $user): void
	{
		if (!$user->hasRole()) {
			$user->setRoles([RoleEnum::User]);
		}
		$hashedPassword = $this->passwordHasher->hashPassword(
			$user,
			$user->getPassword()
		);
		$user->setPassword($hashedPassword);
		$user->setCreatedAt(new \DateTimeImmutable('now'));

		$this->manager->persist($user);
		$this->manager->flush();
	}
}