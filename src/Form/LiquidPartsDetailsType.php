<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\LiquidPartsDetails;
use App\Entity\SpareParts;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiquidPartsDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('spareParts', EntityType::class, [

                'class' => SpareParts::class,
                'placeholder' => 'Select Parts',
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

                'attr' => ['class' => 'span6']


            ])
            ->add('statusType',ChoiceType::class, [
                'choices'  => [
                    'Wastage' => '1',
                    'Sold' => '2'
                ],
                'expanded' => false,
                'mapped' => false,
                'attr' => ['class' => 'span7']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LiquidPartsDetails::class,
        ]);
    }
}
