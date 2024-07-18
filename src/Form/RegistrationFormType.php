<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => ['placeholder' => 'Votre email']
        ])
        ->add('nom', null, [
            'attr' => ['placeholder' => 'Votre nom']
        ])
        ->add('prenom', null, [
            'attr' => ['placeholder' => 'Votre prénom']
        ])
        ->add('telephone', null, [
            'attr' => ['placeholder' => 'Votre téléphone']
        ])
        ->add('adresse', null, [
            'attr' => ['placeholder' => 'Votre adresse']
        ])
        ->add('code_postal', null, [
            'attr' => ['placeholder' => 'Votre code postal']
        ])
        ->add('ville', null, [
            'attr' => ['placeholder' => 'Votre ville']
        ])
        ->add('pays', null, [
            'attr' => ['placeholder' => 'Votre pays']
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'attr' => ['placeholder' => 'Mot de passe']
            ],
            'second_options' => [
                'attr' => ['placeholder' => 'Repeter Mot de passe']
            ],
            'invalid_message' => 'Les mots de passe doivent être identiques.',
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrez votre mot de passe.',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                    'max' => 15,
                ]),
            ],
        ])
            // TO ACTIVATE*****************
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
