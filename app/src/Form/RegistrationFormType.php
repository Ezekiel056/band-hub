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
                'label' => 'J\'accepte que mes données personnelles (adresse email, nom d\'utilisateur) soient collectées et traitées par BandHub afin de gérer mon compte, conformément à notre <a href="/politique-de-confidentialite" class="link" >politique de confidentialité</a>. Je peux exercer mes droits d\'accès, de rectification et de suppression en contactant l\'administrateur.',
                'label_html' => true,
                'constraints' => [
                    new IsTrue(
                        message: 'Vous devez accepter la politique de confidentialité pour créer un compte.',
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
