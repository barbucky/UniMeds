<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('specialization',TextType::class, [
                'label'=>'Spécialisation',
                'constraints'=>[
                    new Regex('/^[a-zA-Zéè\'-]*$/', message: "Ce champ ne peut contenir que des lettres et les caractères - ou '"),
                    new Length(min: 3,minMessage: "Ce champ doit contenir au moins 3 caractères")
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
        ]);
    }
}
