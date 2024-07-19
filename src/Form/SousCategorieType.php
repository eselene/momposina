<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'description', // Utilisez 'description' au lieu de 'id'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousCategorie::class,
        ]);
    }
}
