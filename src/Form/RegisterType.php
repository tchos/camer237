<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Ex: Loïc Bastien'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Ex: Tchos Lolo'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email',
                'attr' => [
                    'placeholder' => 'Ex: lemilanais@caramail.fr'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [ 'placeholder' => 'Ex: Jean4&#JKUI']
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Ex: Ex: Jean4&#JKUI'
                    ]
                ]

            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Annuler'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
