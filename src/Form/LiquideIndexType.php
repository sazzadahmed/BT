<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\SpareParts;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiquideIndexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('findFromDate', TextType::class, [
                'attr' => ['class' => 'span8 js-datepicker','autocomplete'=>'off'],
                'mapped' => false,
                'required'   => false
            ])
            ->add('findToDate', TextType::class, [
                'attr' => ['class' => 'span8 js-datepicker','autocomplete'=>'off'],
                'mapped' => false,
                'required'   => false
            ])

            ->add('spareParts', EntityType::class, [
                // looks for choices from this entity
                'class' => SpareParts::class,
                'placeholder' => 'Select Parts',
                //'choice_label' => 'name',
                'choice_label' => function(SpareParts $spareParts) {
                    return $spareParts->getName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return  $er->createQueryBuilder('c')
                        ->where('c.NumOrQty = :data')
                        ->setParameter('data', 2)
                        ->orderBy('c.name', 'ASC');
                },
                'mapped' => false,
                'empty_data' => null,
                'required'   => false,
                'attr' => ['class' => 'span6']

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('statusType',ChoiceType::class, [
                'choices'  => [
                    'All'   => '',
                    'Wastage' => '2',
                    'Sold'   => '3'
                ],
                'expanded' => false,
                'mapped' => false,
                'required'   => false,
                //'data' => true
                'attr' => ['class' => 'span7']
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
