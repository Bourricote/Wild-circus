<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Performance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class PerformanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('Text', TextType::class, [
                'label' => 'Description',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Choisir une catégorie',
                'choice_label' => 'name',
                'label' => 'Catégorie',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du numéro',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Formats de fichier acceptés : Jpeg, Png',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Performance::class,
        ]);
    }
}
