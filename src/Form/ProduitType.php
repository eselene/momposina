<?php
// src/Form/ProduitType.php
namespace App\Form;

use App\Entity\Produit;
// use App\Form\EntityRepository;
use App\Entity\SousCategorie;
use App\Entity\User;
use App\Repository\SousCategorieRepository;
// use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        //option personnalisée pour détecter l'édition
        $isEdit = $options['is_edit'] ?? false;
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom ',
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
                'label' => 'Visible sur le site web',
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
                'label' => 'Ajouter une image (JPEG, JPG ou PNG)',
                'data_class' => null,
                'mapped' => false, // pas d'association en bdd
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '122880',
                        'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 122 kb.',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG, JPG ou PNG)',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'is_edit' => false, // Ajout de l'option is_edit avec une valeur par défaut            
        ]);
    }
}
