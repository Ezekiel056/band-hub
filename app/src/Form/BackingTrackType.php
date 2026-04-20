<?php

namespace App\Form;

use App\Entity\BackingTrack;
use App\Entity\Instrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BackingTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Intitulé',
                'constraints' => [
                    new NotBlank(message: 'Intitulé obligatoire'),
                    new Length(max:100, maxMessage: 'Trop long (Max 100)')
                ]
    ])
            ->add('fileName',FileType::class, [
                'label' => 'Fichier audio (mp3)',
                'attr' => ['accept' => 'audio/mpeg'],
                'constraints' => [
                    new NotBlank(message: 'Fichier obligatoire'),
                    new File(mimeTypes: ['audio/mpeg'],mimeTypesMessage:'Format de ficher interdit. Fichiers mp3 uniquement'),
                ]
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(message: 'Instrument obligatoire'),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BackingTrack::class,
        ]);
    }
}
