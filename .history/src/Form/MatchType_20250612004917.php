<?php
<?php

namespace App\Form;

use App\Entity\Match;
use App\Entity\Club;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('poule', EntityType::class, [
                'class' => 'App\Entity\Poule',
                'choice_label' => 'nom',
                'label' => 'Poule',
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date du match',
            ])
            ->add('club1', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
                'label' => 'Club Domicile',
            ])
            ->add('club2', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'nom',
                'label' => 'Club Extérieur',
            ])
            ->add('scoreDomicile', IntegerType::class, [
                'label' => 'Score Domicile',
                'required' => true,
            ])
            ->add('scoreExterieur', IntegerType::class, [
                'label' => 'Score Extérieur',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Match::class,
        ]);
    }
}