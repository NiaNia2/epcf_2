<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\User;
use App\Entity\Cepages;
use App\Entity\Regions;
use App\Entity\Bouteille;
use Doctrine\ORM\Cache\Region;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CaveBouteilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image de la bouteille',
                'download_uri' => false,
                'image_uri' => false,
            ])
            ->add('annee')
            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'choice_label' => 'pays',
                'placeholder' => 'Choisissez un pays',
                'mapped' => false,
                'required' => true,
            ])
            ->add('region', ChoiceType::class, [
                'choices' => $options['region'], // ne valide plus les objets Doctrine
                'placeholder' => 'Choisissez une région',
                'mapped' => false, // on gère manuellement
            ])
            ->add('cepage', TextType::class, [
                'mapped' => false,
                'label' => 'Cepage',
                'required' => false,
            ])
            ->add('description')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bouteille::class,
            'region' => [],
        ]);
    }
}
