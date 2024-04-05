<?php

namespace App\Form;

use App\Entity\Civility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CivilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sexe',ChoiceType::class, [
                'choices'=> [
                    'Mr.'=>true,
                    'Mme.'=>false
                ],
                'label' => 'Civilité'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Civility::class,
        ]);
    }
}
