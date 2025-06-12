<?php

namespace App\Form;

use App\Entity\Statistiquesmatch;
use App\Entity\Rencontre;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatistiquematchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rencontre', EntityType::class, [
                'class' => Rencontre::class,
                'choice_label' => 'id',
                'placeholder' => 'Choisissez une rencontre',
                'required' => true,
            ])
            ->add('joueur', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un joueur',
                'required' => true,
            ])
            ->add('buts', IntegerType::class)
            ->add('passes', IntegerType::class)
            ->add('cleansheet', CheckboxType::class, [
                'required' => false,
            ])
            ->add('cartonsjaunes', IntegerType::class)
            ->add('cartonsrouges', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Statistiquesmatch::class,
        ]);
    }
}
