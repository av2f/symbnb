<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration('Titre',"Taper un titre")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web", "Tapez l'adresse web", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfiguration("URL de l'image principale", "image qui donne envie")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration('Introduction', "Donnez une description globale")
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration("Description détaillée", "tapez une description qui donne envie...")
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfiguration("nombre de chambres", "nombre de chambre dispo")
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration("Prix par nuit", "Indiquez le prix que vous voulez")
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class, 
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
