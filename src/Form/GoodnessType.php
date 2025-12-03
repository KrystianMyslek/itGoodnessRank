<?php

namespace App\Form;

use App\Entity\Goodness;
use App\Model\GoodnessTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class GoodnessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name')
            ->add('description')
            ->add('type', EnumType::class, [
                'class' => GoodnessTypeEnum::class,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => function ($choice, string $key, mixed $value): string {
                    return $choice->getLabel();
                },
            ])
            ->add('icon', DropzoneType::class)
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
