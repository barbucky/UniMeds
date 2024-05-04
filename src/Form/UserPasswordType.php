<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserPasswordType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'label'=> 'Ancien mot de passe'
            ])
            ->add('newPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'constraints' => [
                        new NotBlank(),
                        #new Regex('/^(?=.*[!@#$%^&*-_])(?=.*[0-9])(?=.*[A-Z]).{12,}$/', message: "Le mot de passe doit être composé d'au moins 12 caractères et contenir au moins: 1 majuscule, 1 chiffre et un caractère spécial")
                        ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du nouveau mot de passe'
                ],
                'invalid_message' => 'Les nouveaux mots de passe entrés ne concordent pas.'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'row_attr' =>[
                    'class'=>'d-flex justify-content-center'
                ],
                'attr' => [
                    'class' => 'btn vali w-50'
                ]
            ]);
    }

}