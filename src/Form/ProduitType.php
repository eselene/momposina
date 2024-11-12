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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        //option personnalisée pour détecter l'édition
        $isEdit = $options['is_edit'] ?? false;
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom         *',
            ])
            ->add('nomEs', TextType::class, [ 
                'required' => false,
                'label' => 'Nom (espagnol)'  
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 3, 'maxlength' => 255],
            ])
            ->add('pays', TextType::class, [
                'required' => false,
                'label' => 'Pays d\'origine'  
            ])
            ->add('marque', TextType::class, [ 
                'required' => false,
                'label' => 'Marque'  
            ])
            ->add('visibleWeb', CheckboxType::class, [ 
                'label' => 'Visible sur le site web' ,
                // 'data' => true,
                'required' => false,
            ])
            ->add('user', EntityType::class, [ 
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
            ->add('photo1', FileType::class, [ 
                'required' => false,
                'label' => 'Ajouter une image (jpeg ou png)',
                'data_class' => null,
                // unmapped means that this field is not associated to any entity property
                'mapped' => false, // mémo en bdd ou pas
                // make it optional so you don't have to re-upload the  file
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
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG ou PNG)',
                    ])
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'is_edit' => false, // Ajout de l'option is_edit avec une valeur par défaut            

        ]);
    }
}
