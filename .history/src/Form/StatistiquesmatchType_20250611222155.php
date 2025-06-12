<?php

namespace App\Form;

use App\Entity\Statistiquesmatch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatistiquesmatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Ajouter ici les champs du formulaire selon l'entité Statistiquesmatch
        $builder
            // Exemple : ->add('nom_du_champ')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Statistiquesmatch::class,
        ]);
    }
}
