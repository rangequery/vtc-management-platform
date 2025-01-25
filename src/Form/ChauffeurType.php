<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Chauffeur;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChauffeurType extends AbstractType
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
            ->add('prenom', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Prénom',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('voiture', EntityType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Voiture::class,
            ])
            ->add('chatId', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Chat Id',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('telephone', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Téléphone',
                    'inputmode' => 'numeric', // Affiche un clavier numérique sur mobile
                    'pattern' => '\d*', // N'accepte que des chiffres
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chauffeur::class,
        ]);
    }
}
