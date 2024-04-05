<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Civility;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Civility', CivilityType::class, [
                'label' => false
            ]);
            $builder->add('last_name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',

                ])
                ->add('date_Of_Birth', DateType::class, [
                    'label'=>'Date de naissance',
                    'widget'=> 'single_text',
                    'format' => 'yyyy-MM-dd',
                ]);
        $builder->add('Patient', PatientType::class, [
            'label'=> false
        ])

            ->add('email',EmailType::class,[
                'label' => 'Email'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ]);
        $builder->add('Address', AddressType::class,[
        'label' => false
    ])
            ->add('submit', SubmitType::class, [
                'label'=>'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
