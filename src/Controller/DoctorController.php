<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\User;
use App\Form\AppointmentType;
use App\Form\UserType;
use App\Repository\AppointmentRepository;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
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


    #[Route('/Doctor/{id}/Presentation.html', name:'doc_presentation')]
    public function docPresentation($id, DoctorRepository $doctorRepository):Response
    {
        $doctor=$doctorRepository->findOneBy(array('id'=>$id));
        return $this->render('doctor/presentation.html.twig',[
            'id'=>$id,
            'doctor'=>$doctor
        ]);
    }

    #[Route('/Doctor/{id}/Appointement.html', name: 'doc_appointement')]
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
            return $this->render('doctor/appointment.html.twig', [
                'form' => $form,
            ]);

    }
    #[Route('/Doctor/myAppointments.html', name: 'doc_myAppointments')]
    public function appointmentManagement(AppointmentRepository $appointmentRepository):Response
    {

        $user=$this->getUser();
        /** @var  User $user */
        $id=$user->getDoctor()->getId();
        $myAppointments = $appointmentRepository->findBy(['doctor'=>$id]);

        return $this->render('doctor/myAppointments.html.twig',[
            'appointments'=>$myAppointments
        ]);

    }
    #[Route('/Doctor/myAppointments/{id}/Accept.html', name: 'accept_appointment')]
    public function acceptAppointment(Appointment $appointment, EntityManagerInterface $entityManager): RedirectResponse
    {
        $appointment->setStatus('Accepted');
        $entityManager->persist($appointment);
        $entityManager->flush();
        $this->addFlash('success','Le rendez vous est accepté');
        return $this->redirectToRoute('doc_myAppointments');
    }

    #[Route('/Doctor/myAppointments/{id}/Refus.html', name: 'refuse_appointment')]
    public function refusAppointment(Appointment $appointment, EntityManagerInterface $entityManager): RedirectResponse
    {
        $appointment->setStatus('Refus');
        $entityManager->persist($appointment);
        $entityManager->flush();
        $this->addFlash('success','Le rendez vous est refusé');
        return $this->redirectToRoute('doc_myAppointments');
    }

}