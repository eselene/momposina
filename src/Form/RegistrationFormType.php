<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    private const PASSWORD_FIELD_OPTIONS = [
        'attr' => [
            'placeholder' => 'Mot de passe',
            'autocomplete' => 'new-password',
            'class' => 'password-field'
        ],
        'label' => false,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['placeholder' => 'Nom *']
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email *',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('prenom', null, [
                'attr' => ['placeholder' => 'Prénom *']
            ])
            ->add('telephone', null, [
                'attr' => ['placeholder' => 'Téléphone']
            ])
            ->add('adresse', null, [
                'attr' => ['placeholder' => 'Adresse']
            ])
            ->add('code_postal', null, [
                'attr' => ['placeholder' => 'Code postal']
            ])
            ->add('ville', null, [
                'attr' => ['placeholder' => 'Ville']
            ])
            ->add('pays', null, [
                'attr' => ['placeholder' => 'Pays']
            ])
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Madame' => 'Madame',
                    'Monsieur' => 'Monsieur',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => false,
                'placeholder' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Choisissez votre civilité.']),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                        'autocomplete' => 'new-password',
                        'class' => 'password-field'
                    ],
                    'label' => false,
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Répétez le mot de passe',
                        'autocomplete' => 'new-password',
                        'class' => 'password-field'  
                    ],
                    'label' => false,
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Entrez votre mot de passe.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 15,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions générales.',
                    ]),
                ],
                'label' => 'J\'accepte les conditions générales'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
