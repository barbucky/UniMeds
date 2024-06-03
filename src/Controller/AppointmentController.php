<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\User;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/{id}/Appointement.html', name: 'doc_appointement')]
    public function selectAppointement(Request $request, Doctor $doctor, EntityManagerInterface $entityManager):Response
    {
        /** @var User $user */
        $user=$this->getUser();
        $appointment = new Appointment();
        $appointment->setDoctor($doctor);
        $appointment->setPatient($user->getPatient());

        $form= $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        $nomDoc=$doctor->getUser()->getLastName();


        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setStatus('En attente');
            $chosenDate=$appointment->getDateOfAppointment()->format('d/m/Y');
            $chosenHour=$appointment->getStartHour()->format('h:i');

            $entityManager->persist($appointment);
            $entityManager->flush();

            $this->addFlash('success','Votre demande de rendez-vous du '.$chosenDate.' à '.$chosenHour.' avec le docteur '.$nomDoc.' a bien été enregistrée');
            return $this->redirectToRoute('app_default_home');


        }
        return $this->render('appointment/appointment.html.twig', [
            'form' => $form,
            'nomDoc'=>$nomDoc
        ]);

    }

}