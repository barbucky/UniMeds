<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\User;
use App\Form\AppointmentType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    #[Route('/mon_compte/Doctor.html', name: 'Doctor')]
    public function doctorHome():Response
    {
        return $this->render('/doctor/doctorhome.html.twig');

    }
    #[Route('/mon_compte/Doctor/modification.html', name: 'DoctorUpdate')]
    public function editDoctor(Request $request, EntityManagerInterface $em):Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->remove('Patient');
        $form->remove('Address');

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user=$form->getData();
            $user->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour');

            return $this->redirectToRoute('Doctor');

        }

        return $this->render('doctor/doctorModification.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/Doctor/Appointement.html')]
    public function selectAppointement(Request $request, Doctor $doctor):Response
    {
        /** @var User $user */
        $user=$this->getUser();
        #$creneau = ['14:00', '15:00','16:00','17:00','18:00'];
        $appointment = new Appointment();
        $appointment->setDoctor($doctor);
        #$appointment->setPatient($user->getPatient());

        $form= $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
        $chosenHour=$appointment->getStartHour();


        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);

        }
            return $this->render('doctor/appointment.html.twig', [
                'form' => $form,
            ]);

    }

}