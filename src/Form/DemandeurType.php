<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Demandeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Nom',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('adresse', EntityType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Adresse::class,
                'choice_label' => 'nom',
            ])
            ->add('telephone', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Telephone',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => '@',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demandeur::class,
        ]);
    }
}
