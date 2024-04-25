<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    #[Route('/mon_compte/Patient.html',name:'Patient')]
    public function PatientHome()
    {
        return $this->render('patient/patienthome.html.twig');

    }

    #[Route('/mon_compte/Patient/modification.html',name: 'PatientUpdate')]
    public function editPatient(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();


            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Votre profil a été mis à jour');

            return $this->redirectToRoute('Patient');

        }

        return $this->render('patient/patientModification.html.twig',[
            'form'=>$form
    ]);
    }

}

