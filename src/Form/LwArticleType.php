<?php

namespace App\Form;

use App\Entity\LwArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LwArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'=>'Titre de l\'article',
                'attr'=>['placeholder'=>'titre de l\'article']
            ])
            ->add('content', TextareaType::class,[
                'label'=>'le contenu de l\'article',
                'attr'=>['placeholder'=>'contenu de l\'article']
            ])
            ->add('photo', TextType::class,[
                'label'=>'Image de l\'article',
                'attr'=>['placeholder'=>'image de l\'article']
            ])
            ->add('save',SubmitType::class, ['label'=>'Enregistrer l\'article','attr'=>['class'=>'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LwArticle::class,
        ]);
    }
}
