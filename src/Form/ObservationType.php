<?php

namespace App\Form;

use App\Entity\Observation;
use App\Repository\OiseauRepository;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType;
use Symfony\Component\Routing\RouterInterface;

class ObservationType extends AbstractType
{
    private $router;
    public function __construct( OiseauRepository $repo_bird, RouterInterface $router)
    {
        $this->router = $router;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label'=>'Nom de l\'observation','attr' => [
                'autocomplete'=> 'off'
            ]))
            ->add('longitude', TextType::class, array('label'=>'Longitude'))
            ->add('latitude', TextType::class, array('label'=>'Latitude'))
            ->add('photo', FileType::class, array('required'   => false,
                'empty_data' => null))
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Observation::class,
            'attr' => [
                'autocomplete'=> NULL,
                'class' => 'js-observation-autocomplete',
                'data-autocomplete-url' => $this->router->generate('utility_bird')
            ]
        ]);
    }
}
