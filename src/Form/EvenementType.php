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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //option personnalisée pour détecter l'édition
        $isEdit = $options['is_edit'] ?? false;
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
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('plageHeure', TextType::class, [
                'required' => false,
                'label' => 'Horaire',
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
            ])
            ->add('prix', TextType::class, [
                'required' => false,
                'label' => 'Prix',
            ])
            ->add('visibleWeb', CheckboxType::class, [
                'label' => 'Visible',
                // 'data' => true,
                'required' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Utilisateur associé',
            ])
            ->add('photo1', FileType::class, [
                'required' => !$isEdit, // Le champ est obligatoire seulement si ce n'est pas une édition
                'label' => 'Ajouter une image (jpeg ou png)',
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
            ])
        ;

        // Ajout d'un écouteur pour gérer les formulaires en mode édition
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $evenement = $event->getData();
            $form = $event->getForm();

            if ($evenement && $evenement->getId()) {
                // En mode édition, rendre le champ photo1 non obligatoire
                $form->add('photo1', FileType::class, [
                    'required' => false,
                    'label' => 'Modifier l\'image (laissez vide pour conserver l\'image actuelle)',
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
        });
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
            'is_edit' => false, // Ajout de l'option is_edit avec une valeur par défaut            
        ]);
    }
}
