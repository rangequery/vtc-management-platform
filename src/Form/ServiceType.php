<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Chauffeur;
use App\Entity\Client;
use App\Entity\CourseType;
use App\Entity\Demandeur;
use App\Entity\Service;
use App\Entity\SousTraitent;
use App\Entity\Status;
use App\Entity\Type;
use App\Repository\AdresseRepository;
use App\Repository\ChauffeurRepository;
use App\Repository\DemandeurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serviceAt', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'From',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])

            ->add('type', EntityType::class, [
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Type::class,
                'choice_label' => 'nom',
            ])

            ->add('status', EntityType::class, [
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Status::class,
                'choice_label' => 'nom',
            ])

            ->add('sousTraitent', EntityType::class, [
                'class' => SousTraitent::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => '',
                'label' => false,
                'attr' => [
                    'class' => 'form-select ',
                ],
            ])

            ->add('pax', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'PAX',
                    'inputmode' => 'numeric', // Affiche un clavier numérique sur mobile
                    'pattern' => '\d*', // N'accepte que des chiffres
                    'min' => 0, // optionnel, pour ne permettre que des nombres positifs
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('bagages', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'Bagages',
                    'inputmode' => 'numeric', // Affiche un clavier numérique sur mobile
                    'pattern' => '\d*', // N'accepte que des chiffres
                    'min' => 0, // optionnel, pour ne permettre que des nombres positifs
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('montantHt', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Montant HT',
                    'inputmode' => 'numeric', // Affiche un clavier numérique sur mobile
                    'pattern' => '\d*', // N'accepte que des chiffres
                    'min' => 0, // optionnel, pour ne permettre que des nombres positifs
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('pickUpFrom', EntityType::class, [
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Adresse::class,
                'query_builder' => function (AdresseRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC'); // Remplacez 'nom' par le champ souhaité
                },
                'choice_label' => 'nom',
            ])
            ->add('pickUpTo', EntityType::class, [
                'attr' => [
                    'class' => 'form-select ',
                ],
                'placeholder' => '',
                'class' => Adresse::class,
                'query_builder' => function (AdresseRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC'); // Remplacez 'nom' par le champ souhaité
                },
                'choice_label' => 'nom',
            ])
            ->add('chauffeur', EntityType::class, [
                'required' => false,
                'placeholder' => '',
                'label' => false,
                'attr' => [
                    'class' => 'form-select ',
                ],
                'class' => Chauffeur::class,
                'query_builder' => function (ChauffeurRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC'); // Remplacez 'nom' par le champ souhaité
                },
            ])
            ->add('informationComplementaire', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control',
                    'rows' => '3', // Nombre de lignes
//                    'cols' => '25' // Nombre de colonnes
                ]
            ])
            // Demandeur
            ->add('demandeur', EntityType::class, [
                'class' => Demandeur::class,
                'query_builder' => function (DemandeurRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC'); // Remplacez 'nom' par le champ souhaité
                },

                'required' => false,
                'placeholder' => '',
                'label' => false,
                'attr' => [
                    'class' => 'form-select ',
                ],
            ])
            ->add('referenceNumber', null, [
                'attr' => [
                    'class' => 'form-control ',
                    'placeholder' => 'N° Flight / N° Train / N° Room',
                    // Ajoutez d'autres attributs si nécessaire
                ],
            ])
            ->add('infoClient', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control',
                    'rows' => '3', // Nombre de lignes
//                    'cols' => '25' // Nombre de colonnes
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
