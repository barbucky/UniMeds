<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street_name', TextType::class,[
                'label'=>'Rue',
                'constraints'=>[
                    new Regex('/^[a-zA-Z0-9-_.,\']*$/', message: "Ce champ ne peut contenir que des lettres, des chiffres ou les caractères _ - , . ' "),
                    new Length(min: 2,minMessage: "Ce champ doit contenir au moins 2 caractères")
                ]
            ])
            ->add('city_name', TextType::class,[
                'label'=>'Ville',
                'constraints'=>[
                    new Regex('/^[a-zA-Z0-9-_.,\']*$/', message: "Ce champ ne peut contenir que des lettres, des chiffres ou les caractères _ - , . ' "),
                    new Length(min: 1,minMessage: "Ce champ doit contenir au moins 1 caractères")
                ]
            ])
            ->add('postal_code', TextType::class,[
                'label'=>'Code postal',
                'constraints'=>[
                    new Regex('/^[a-zA-Z0-9]*$/', message: "Ce champ ne peut contenir que des lettres ou des chiffres"),
                    new Length(min: 5, max: 5, exactMessage: "Ce champ doit contenir 5 caractères")
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
