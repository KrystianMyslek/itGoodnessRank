<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('user')]
class UserController extends AbstractController
{

	#[Route('/login', name: 'user_login')]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{

		$error = $authenticationUtils->getLastAuthenticationError();

		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('user/login.html.twig', [
			'login_form'    => true,
			'last_username' => $lastUsername,
			'error'         => $error,
		]);
	}

	#[Route('/register', name: 'user_register')]
	public function register(
		Request $request,
		Security $security,
		UserService $userService
	): Response {

		$user = new User();

		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$userService->create($user);

			$security->login($user);

			return $this->redirectToRoute('goodness_ranking');
		}

		return $this->render('user/register.html.twig', [
			'login_form' => true,
			'form'       => $form,
		]);
	}
}