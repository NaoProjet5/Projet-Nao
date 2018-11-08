<?php

namespace App\Form;

use App\Entity\Observation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label'=>'Nom de l\'observation'))
            ->add('coordonneesGps', TextType::class, array('label'=>'Les coordonnées GPS'))
            ->add('photo', FileType::class, array('label'=>'Photo de l\'observation'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Observation::class,
        ]);
    }
}