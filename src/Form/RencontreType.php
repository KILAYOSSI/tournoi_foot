<?php

namespace App\Form;

use App\Entity\Rencontre;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RencontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('club1', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez le club 1',
                'required' => true,
            ])
            ->add('club2', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez le club 2',
                'required' => true,
            ])
            ->add('dateHeure', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('scoreclub1', IntegerType::class, [
                'required' => false,
            ])
            ->add('scoreclub2', IntegerType::class, [
                'required' => false,
            ])
            ->add('joue', CheckboxType::class, [
                'required' => false,
                'label' => 'Match joué',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rencontre::class,
        ]);
    }
}
