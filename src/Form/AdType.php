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

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Saisissez le titre de l\'annonce'))
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration('Adresse Web', 'Adresse Web (automatique)', ['required' => false])
            )
            ->add('coverImage', UrlType::class, $this->getConfiguration('Url de l\'image principale', 'Donnez une adresse !'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'donnez une description globale de l\'annonce'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description détaillé', 'Tapez une description qui donne envie'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambres', 'Nombre de chambres disponibles'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit', 'Indiquez le prix par nuit'))
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type'   => ImageType::class,
                    'allow_add'    => true,
                    'allow_delete' => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
