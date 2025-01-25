<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Nom',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('prefix', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Préfixe',
                    'maxlength' => 5,
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('adresse', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Adresse',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('codePostal', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Code Postal',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('ville', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Ville',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
