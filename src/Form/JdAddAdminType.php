<?php

namespace App\Form;

use App\Entity\JdUsers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;

class JdAddAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstname', TextType::class)
            ->add('email', TextType::class)
            ->add('roles', ChoiceType::class,
                [
                    'choices'       => [
                        'Utulisateur'      => 'ROLE_USER',
                        'Naturatliste'     => 'ROLE_NATURALIST',
                        'Auteur'           => 'ROLE_AUTHOR',
                        'Super admin'      => 'ROLE_SUPER_ADMIN'
                    ]
                ])
            ->add('password', PasswordType::class)
            ->add('passwordConfirm', PasswordType::class)
            ->add('recaptcha', EWZRecaptchaType::class, [
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
            ->add('Enregistrer', SubmitType::class);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray)
                {
                    return implode(',', $rolesAsArray);
                },
                function ($rolesAsString)
                {
                    return explode( ',', $rolesAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JdUsers::class,
        ]);
    }
}
