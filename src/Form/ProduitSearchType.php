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
    private const PLACEHOLDER = 'Rechercher par Nom';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => self::LABEL,
                'required' => false,
                'attr' => [
                    'placeholder' => self::PLACEHOLDER,
                    'class' => 'form-control',
                    'onkeydown' => 'if (event.key === "Enter") this.form.submit();'
                ],
                'label_attr' => [
                    'class' => 'search-label mr-2'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
