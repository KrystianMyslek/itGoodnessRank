<?php

namespace App\Factory;

use App\Entity\User;
use App\Model\RoleEnum;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
	public function __construct(
		private UserPasswordHasherInterface $passwordHasher
	) {
		parent::__construct();
	}

	public static function class(): string
	{
		return User::class;
	}

	protected function defaults(): array|callable
	{
		$name = self::faker()->unique()->name();

		return [
			'login'    => $name,
			'email'    => self::faker()->unique()->safeEmail(),
			'roles'    => [RoleEnum::User],
			'password' => $name . "123",
		];
	}

	protected function initialize(): static
	{
		return $this
			->afterInstantiate(function (User $user): void {
				$user->setPassword(
					$this->passwordHasher->hashPassword($user, $user->getPassword())
				);
			})
		;
	}
}
