<?php

namespace App\Form;

use App\Entity\SousTraitent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousTraitentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('telephone', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Telephone',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousTraitent::class,
        ]);
    }
}
