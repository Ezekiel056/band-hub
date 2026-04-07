<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,options : [
                'label' => 'Adresse email',
                'constraints' => [
                    new NotBlank(
                        message: 'Champ obligatoire',
                    ),
            ]])
            ->add('username',options : [
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new NotBlank(
                        message: 'Champ obligatoire',
                    )]

            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'Veuillez accepter les conditions d\'utilisation.',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Champ obligatoire',
                    ),
                    new Length(
                        min: 6,
                        max: 4096,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                    ),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, options : [
                'label' => 'Confirmez votre mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(
                        message: 'Champ obligatoire',
                    ),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new Callback(function ($data, ExecutionContextInterface $context) {
                    $form = $context->getRoot();
                    $password = $form->get('plainPassword')->getData();
                    $confirm   = $form->get('confirmPassword')->getData();

                    if ($password !== $confirm) {
                        $context
                            ->buildViolation('Les mots de passe ne correspondent pas')
                            ->atPath('confirmPassword')
                            ->addViolation();
                    }
                }),
            ],
        ]);
    }
}
