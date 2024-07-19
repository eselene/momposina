<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('nomEs')
            ->add('description')
            ->add('pays')
            ->add('marque')
            ->add('poids')
            ->add('unitePoids')
            ->add('ingredients')
            ->add('allergenes')
            ->add('certification')
            ->add('photo1')
            ->add('photo2')
            ->add('prix')
            ->add('visibleWeb')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('sousCategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
