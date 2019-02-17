<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content',TextareaType::class,[
                'label'=>'Commentaire sur cet article',
                'attr'=>['placeholder'=>'contenu du commentaire']
            ]);
            $builder->add('recaptcha', EWZRecaptchaType::class, [
                'label'=>'Je ne suis pas un robot',
                'attr' => [
                    'options' => [
                        'theme' => 'light',
                        'type'  => 'image',
                        'size'  => 'normal',
                        'defer' => true,
                        'async' => true,
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
