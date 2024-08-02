<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 3],
            ])
            ->add('date', TextType::class, [
                'label' => 'Date',
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{4}-\d{2}-\d{2}$/',
                        'message' => 'Veuillez entrer une date au format YYYY-MM-DD',
                    ]),
                ],
            ])

            // ->add('date', TextType::class, [
            //     'label' => 'Date',
            //     'constraints' => [
            //         new Assert\Regex([
            //             'pattern' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/',
            //             'message' => 'Veuillez entrer une date et une heure au format YYYY-MM-DD HH:MM',
            //         ]),
            //     ],
            // ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('plageHeure', TextType::class, [
                'label' => 'Plage Horaire',
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
            ])
            ->add('prix', TextType::class, [
                'label' => 'Prix',
            ])
            ->add('visibleWeb', CheckboxType::class, [
                'label' => 'Visible sur le site web',
                'data' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Utilisateur associé',
            ])
            ->add('photo1', FileType::class, [
                'required' => false,
                'label' => 'Ajouter une image (jpeg, jpg, png)',
                'data_class' => null,
                'mapped' => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG ou PNG)',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
