<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
// rappel : ApplicationType contient la fonction getConfiguration et herite de AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                DateType::class,
                $this->getConfiguration("Date d'arrivée", "Date à laquelle vous arrivez",
                ["widget"=>"single_text"])
            )
            ->add(
                'endDate',
                DateType::class,
                $this->getConfiguration("Date de départ", "Date à laquelle vous partez",
                ["widget"=>"single_text"])
            )
            // false pour label = pas de label
            ->add(
                'comment',
                TextareaType::class,
                $this->getConfiguration(false,"Si vous avez un commentaire, n'hésitez pas à en faire part", [
                    "required"=>false
                ] )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
