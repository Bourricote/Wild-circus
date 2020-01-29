<?php

namespace App\Form;

use App\Entity\TourSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TourSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city',TextType::class, [
        'required' => false,
        'attr' => [
            'placeholder' => 'Ville',
        ],
        'label' => false,
    ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TourSearch::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
