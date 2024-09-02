<?php
// src/Form/ProductSearchType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitSearchType extends AbstractType
{
    private const LABEL = 'Rechercher un produit';
    private const PLACEHOLDER = 'Rechercher';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => self::LABEL,
                'required' => false,
                'attr' => [
                    'placeholder' =>  self::PLACEHOLDER,
                    'class' => 'form-control', // Classe Bootstrap pour le champ de recherche
                    'onkeydown' => 'if (event.key === "Enter") this.form.submit();' // Soumission sur Enter
                ],
                'label_attr' => [
                    'class' => 'search-label mr-2' // Espacement avec la classe Bootstrap mr-2 (margin-right)
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
