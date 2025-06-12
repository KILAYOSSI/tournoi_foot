<?php

namespace App\Form;

use App\Entity\Match;
use App\Entity\Poule;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('poule', EntityType::class, [
                'class' => Poule::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez une poule',
                'required' => true,
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Match::class,
        ]);
    }
}
