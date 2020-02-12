<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\EntryDetails;
use App\Entity\SpareParts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qty',TextType::class,['attr' => ['class'=>'span12' ]])
            ->add('price',TextType::class,['attr' => ['class'=>'span11' ]])
            ->add('partsDescription',TextareaType::class,['attr' => ['class'=> 'span8','colspan'=> "10","rows"=> "4"]])
            ->add('tirePosition',ChoiceType::class,['attr'=>['onchange'=>'changeSelectOption(this)']])
            ->add('spareParts',EntityType::class,[
                'class' =>SpareParts::class,
                'placeholder' => 'Select',
                'choice_label' => 'name',
            ])
            ->add('car',EntityType::class,
                [
                    'class' => Car::class,
                    'placeholder' => 'Select',
                    'choice_label' => 'chessis',
                ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-success','style'=>'margin-top:20px']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EntryDetails::class,
        ]);
    }
}
