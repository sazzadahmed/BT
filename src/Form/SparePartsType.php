<?php

namespace App\Form;

use App\Entity\SpareParts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SparePartsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,array(
                'attr' => ['class' => 'span4']
            ))
            ->add('mileage',ChoiceType::class,array('choices'  => [
                'Yes' => '1',
            ],
                'placeholder' => 'Select Any Type',
                'expanded' => false,
                'multiple'=> false,
                'required' =>false,
            ))
            ->add('isTire',ChoiceType::class,array('choices'  => [
                'Yes' => '1',
            ],
                'placeholder' => 'Select Any Type',
                'expanded' => false,
                'multiple'=> false,
                'required' =>false,
            ))
            ->add('NumOrQty',ChoiceType::class,array('choices'  => [
                'Solid' => '1',
                'Liquid' => '2',
            ],
                'placeholder' => 'Select Any Type',
                'expanded' => false,
                'multiple'=> false,

            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SpareParts::class,
        ]);
    }
}
