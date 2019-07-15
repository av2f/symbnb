<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class BookingType extends ApplicationType
// rappel : ApplicationType contient la fonction getConfiguration et herite de AbstractType

{
    private $transformer;
    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                TextType::class,
                $this->getConfiguration("Date d'arrivée", "Date à laquelle vous arrivez")
            )
            ->add(
                'endDate',
                TextType::class,
                $this->getConfiguration("Date de départ", "Date à laquelle vous partez")
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

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
