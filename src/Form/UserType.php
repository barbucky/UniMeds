<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Civility;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', EntityType::class, [
                'class' => Civility::class,
                'choice_label' => 'id',
            ])
            ->add('last_name')
            ->add('first_name')
            ->add('email')
            ->add('phone')
            ->add('password')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
