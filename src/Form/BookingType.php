<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_date', TextType::class, $this->getConfiguration(
                "Date d'arrivée",
                "La date à laquelle vous comptez arriver"
            ))
            ->add('endDate', TextType::class, $this->getConfiguration(
                "Date de départ",
                "La date à laquelle vous souhaitez partir"
            ))
            ->add('comment', TextareaType::class, $this->getConfiguration(
                false,
                "Laissez-nous un commentaire",
                ["required" => false]
            ));

        $builder->get('start_date')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
