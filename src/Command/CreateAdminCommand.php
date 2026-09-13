<?php

namespace App\Command;

use App\Entity\User;
use App\Model\RoleEnum;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'app:create-admin',
	description: 'Create an admin user',
)]
class CreateAdminCommand extends Command
{
	public function __construct(private EntityManagerInterface $entityManager, private UserService $userService)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this
			->addArgument('login', InputArgument::REQUIRED, 'Login')
			->addArgument('email', InputArgument::REQUIRED, 'Email')
			->addArgument('password', InputArgument::REQUIRED, 'Password')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$login = $input->getArgument('login');
		$email = $input->getArgument('email');
		$password = $input->getArgument('password');

		$existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
		if ($existingUser) {
			$io->error(sprintf('User with this email exists!', $email));
			return Command::FAILURE;
		}

		$user = new User();
		$user->setLogin($login);
		$user->setEmail($email);
		$user->setPassword($password);
		$user->setRoles([RoleEnum::Admin]);

		$this->userService->create($user);

		$io->success(sprintf('Admin %s was created succesfully!', $login));

		return Command::SUCCESS;
	}
}
