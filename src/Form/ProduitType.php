<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\User;
// use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\SousCategorieRepository;
// use App\Form\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom         *',
            ])
            ->add('nomEs', TextType::class, [ // Use TextType for Spanish name
                'required' => false,
                'label' => 'Nom (espagnol)' // Clearer label for user
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('pays', TextType::class, [
                'required' => false,
                'label' => 'Pays d\'origine' // Clearer label for user
            ])
            ->add('marque', TextType::class, [ // Use TextType for brand
                'required' => false,
                'label' => 'Marque' // Clearer label for user
            ])
            ->add('visibleWeb', CheckboxType::class, [ // Use CheckboxType for boolean visibility
                'label' => 'Visible sur le site web' ,
                'data' => true,
            ])
            ->add('user', EntityType::class, [ // Use EntityType for user association
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Utilisateur associé' 
            ])
            ->add('sousCategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'choice_label' => 'description',
                'query_builder' => function (SousCategorieRepository $repository) {
                    return $repository->findAlphabeticallyOrdered();
                },
                'label' => 'Sous-catégorie' 
            ])            
            ->add('photo1', FileType::class, [ // il faut traiter ce fichier là dans le controller
                'required' => false,
                'label' => 'Ajouter une image (jpeg, jpg, png)',
                'data_class' => null,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false, // mémo en bdd ou pas
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG or PNG)',
                    ])
                ],
            ]);
            // Add fields for 'poids', 'unitePoids', 'ingredients', 'allergenes', and 'certification'
            // if needed, using appropriate form field types and labels
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}