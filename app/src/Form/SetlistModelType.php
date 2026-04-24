<?php

namespace App\Form;

use App\Entity\Band;
use App\Entity\SetlistModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SetlistModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom de la setlist',
                'constraints' => [
                    new NotBlank(message : 'Nom obligatoire.'),
                    new Length(max: 100, maxMessage: 'Trop long (max 100)'),
                ]
            ])
            ->add('color', ChoiceType::class, [
                'label' => 'Couleur de la setlist',
                'choices' => [
                    'Violet' => '#ECE4FD',
                    'Bleu' => '#D6FAFF',
                    'Jaune' => '#FDFBE4',
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => '#ECE4FD',
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SetlistModel::class,
        ]);
    }
}
