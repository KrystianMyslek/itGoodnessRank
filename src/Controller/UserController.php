<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\RoleEnum;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('user')]
class UserController extends AbstractController
{
    public function __construct(private Security $security) {

    }
    
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
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher
    ) : Response
    {

        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($user->getPassword() != $request->request->all()['user']['repeat_password']) {

            }
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $user->setRoles([RoleEnum::User]);
            $user->setCreatedAt(new \DateTimeImmutable('now'));

            $manager->persist($user);
            $manager->flush();

            $this->security->login($user);

            return $this->redirectToRoute('goodness_ranking');
        }

        return $this->render('user/register.html.twig', [
            'login_form' => true,
            'form'       => $form,
        ]);
    }
}