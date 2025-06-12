<?php

namespace App\Form;

use App\Entity\Statistiquesmatch;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StatistiquesmatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('joueur', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
                'label' => 'Joueur',
                'required' => true,
            ])
            ->add('buts', IntegerType::class, [
                'label' => 'Buts',
                'required' => true,
            ])
            ->add('passes', IntegerType::class, [
                'label' => 'Passes Décisives',
                'required' => true,
            ])
            ->add('cartonsJaunes', IntegerType::class, [
                'label' => 'Cartons Jaunes',
                'required' => true,
            ])
            ->add('cartonsRouges', IntegerType::class, [
                'label' => 'Cartons Rouges',
                'required' => true,
            ])
            ->add('cleansheet', CheckboxType::class, [
                'label' => 'Clean Sheet',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Statistiquesmatch::class,
        ]);
    }
}