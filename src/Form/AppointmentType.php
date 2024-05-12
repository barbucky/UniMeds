<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_of_appointment', DateType::class, [
                'widget' => 'single_text',
                'label'=>'Jour de la consultation:',
                'constraints'=>[
                    new NotNull(),
                    new NotBlank()
                ]
            ])
            ->add('start_hour', TimeType::class, [
                'widget' => 'single_text',
                'label'=>'Heure de la consultation:',
                /*'hours'=>[
                    '08'=>'08',


                ],
                'minutes'=>['00'=>0],*/
                'constraints'=>[
                    new NotNull(),
                    new NotBlank()
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
