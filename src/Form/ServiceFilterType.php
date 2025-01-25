<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Chauffeur;
use App\Entity\Demandeur;
use App\Entity\SousTraitent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Adresse', EntityType::class, [
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez une adresse',
                'class' => Adresse::class,
                'choice_label' => 'nom',
                'required' => false,
            ])
            ->add('chauffeur', EntityType::class, [
                'required' => false,
                'placeholder' => 'Sélectionnez un chauffeur',
                'class' => Chauffeur::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('demandeur', EntityType::class, [
                'required' => false,
                'placeholder' => 'Sélectionnez un demandeur',
                'class' => Demandeur::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('sousTraitent', EntityType::class, [
                'required' => false,
                'placeholder' => 'Sélectionnez un sous-traitant',
                'class' => SousTraitent::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'À partir de'],
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Jusqu\'à'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
