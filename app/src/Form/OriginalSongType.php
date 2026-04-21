<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class OriginalSongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileName',FileType::class, [
                'label' => 'Fichier audio (mp3)',
                'attr' => ['accept' => 'audio/mpeg'],
                'constraints' => [
                    new NotBlank(message: 'Fichier obligatoire'),
                    new File(mimeTypes: ['audio/mpeg'],mimeTypesMessage:'Format de ficher interdit. Fichiers mp3 uniquement'),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
