<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Song;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, options: [
                'label' => 'Titre',
            ])
            ->add('duration', NumberType::class, options: [
                'label' => 'Durée'
            ])
            ->add('bpm')

            ->add('status', options: [
                'label' => 'Statut',
            ])
            ->add('artist', EntityType::class, [
                'label' => 'Artiste',
                'class' => Artist::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
