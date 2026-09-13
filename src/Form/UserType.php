<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{

		$builder
			->add('login', TextType::class, [
				'required'    => false,
				'constraints' => [
					new NotBlank(),
					new Length([
						'min' => 4,
						'max' => 22,
					]),
				],
			])
			->add('password', RepeatedType::class, [
				'type'           => PasswordType::class,
				'required'       => false,
				'first_options'  => [
					'label' => 'Hasło',
					'attr'  => ['autocomplete' => 'new-password'],
				],
				'second_options' => [
					'label' => 'Powtórz hasło',
					'attr'  => ['autocomplete' => 'new-password'],
				],
				'constraints'    => [
					new NotBlank(),
					new Length([
						'min' => 6,
						'max' => 30,
					]),
				],
			])
			->add('email', EmailType::class, [
				'required'    => false,
				'constraints' => [
					new NotBlank(),
					new Email(),
				],
			])
			->add('save', SubmitType::class)
		;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class'  => User::class,
			'constraints' => [
				new UniqueEntity([
					'fields'    => 'login',
					'errorPath' => 'login',
				]),
				new UniqueEntity([
					'fields'    => 'email',
					'errorPath' => 'email',
				]),
			],
		]);
	}
}
