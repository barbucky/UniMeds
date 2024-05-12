<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Clock\now;

class PatientController extends AbstractController
{
    #[Route('/mon_compte/Patient.html',name:'Patient')]
    public function patientHome():Response
    {
        return $this->render('patient/patienthome.html.twig');

    }

    #[Route('/mon_compte/Patient/modification.html',name: 'PatientUpdate')]
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
            $user->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour');

            return $this->redirectToRoute('Patient');

        }

        return $this->render('patient/patientModification.html.twig',[
            'form'=>$form
    ]);
    }

}

