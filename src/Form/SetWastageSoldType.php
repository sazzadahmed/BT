<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetWastageSoldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('soldQuantity', TextType::class,array(
                'attr' => ['class' => 'span4'],
                'mapped'=>false,

            ))
            ->add('soldPrice', TextType::class,array(
                'attr' => ['class' => 'span4'],
                'mapped'=>false,

            ))
            ->add('spearsParts', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('isEditedID', HiddenType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
