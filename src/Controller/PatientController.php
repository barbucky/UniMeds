<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Clock\now;

class PatientController extends AbstractController
{
    #[Route('/mon_compte/Patient.html',name:'app_patient_patienthome')]
    public function patientHome():Response
    {
        return $this->render('patient/patienthome.html.twig');

    }

    #[Route('/mon_compte/Patient/modification.html',name: 'app_patient_patientUpdate')]
    public function editPatient(Request $request, EntityManagerInterface $em):Response
    {

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->remove('Doctor');

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){


            /** @var User $user */
            $user=$form->getData();

            $last_name=$user->getLastName();
            $last_name=strtoupper(strip_tags($last_name));
            $user->setLastName($last_name);

            $first_name=$user->getFirstName();
            $first_name=ucwords(strip_tags($first_name),"\ \-");
            $user->setFirstName($first_name);

            $user->setFullName($first_name.' '.$last_name);

            $user->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour');

            return $this->redirectToRoute('app_patient_patienthome');

        }

        return $this->render('patient/patientModification.html.twig',[
            'form'=>$form
    ]);
    }

    #[Route('/Patient/myAppointment.html', name: 'app_patient_myAppointments')]
        public function appointmentManagementPatient(AppointmentRepository $appointmentRepository):Response
    {

        $user=$this->getUser();
        /** @var  User $user */
        $id=$user->getPatient()->getId();
        $myAppointments = $appointmentRepository->findBy(['patient'=>$id]);

        return $this->render('patient/myAppointments.html.twig',[
            'appointments'=>$myAppointments
        ]);

    }

    #[Route('/Patient/myAppointment/{id}/annule.html', name: 'app_patient_annulappointment')]
    public function annulAppointment(Appointment $appointment, EntityManagerInterface $entityManager): RedirectResponse
    {
        $appointment->setStatus('Annule');
        $entityManager->persist($appointment);
        $entityManager->flush();
        $this->addFlash('success','votre rendez vous a bien été annulé');
        return $this->redirectToRoute('');
    }

}

