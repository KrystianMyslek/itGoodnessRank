<?php

namespace App\Form;

use App\Entity\Goodness;
use App\Model\GoodnessTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Dropzone\Form\DropzoneType;

class GoodnessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $iconFile_constraints = [
            new Image(
                maxSize: '1024k',
                mimeTypes: [
                    'image/*',
                ],
                mimeTypesMessage: 'Dozwolone tylko format obrazu',
            )
        ];

        if (empty($options['data']->getId())) {
            $iconFile_constraints[] = new NotBlank([
                'message' => 'To pole nie może byc puste',
            ]);
        }

        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'To pole nie może być puste',
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'To pole nie może być puste',
                    ]),
                ]
            ])
            ->add('type', EnumType::class, [
                'class' => GoodnessTypeEnum::class,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => function ($choice): string {
                    return $choice->getLabel();
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'Typ musi być wybrany',
                    ]),
                ]

            ])
            ->add('iconFile', DropzoneType::class, [
                'attr' => [
                    'data-controller' => 'iconDropzone'
                ],
                'mapped' => false,
                'required' => true,
                'constraints' => $iconFile_constraints
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Goodness::class,
        ]);
    }
}
