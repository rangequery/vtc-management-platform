<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
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
            ->add('adresse', EntityType::class, [
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Adresse::class,
                'choice_label' => 'nom',
            ])
            ->add('telephone', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Telephone',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('email', EmailType::class, [
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
            'data_class' => Client::class,
        ]);
    }
}
