<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom',TextType::class)
            ->add('reference',TextType::class)
            ->add('description',TextType::class)
            ->add('prix',NumberType::class)
            ->add('categorie', EntityType::class,
             ['class' => Categorie::class,
             'choice_label' => function (Categorie $categorie) {
                return $categorie->getNom();
            },
            ]) 
            ->add('statut', ChoiceType::class,
             ['choices'  => array(
                    'activé' => 'activé',
                    'désactiver' => 'désactiver'
             ),
            ]) 
            ->add('image',FileType::class
            ,['mapped'=>false
            ,'required' => false]
            )

            ->add('imgproduit',FileType::class
            ,['mapped'=>false,
            'multiple' => true
            ,'required' => false
            ]
            )

            ->add('save', SubmitType::class)
            
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
