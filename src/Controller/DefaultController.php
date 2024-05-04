<?php

namespace App\Controller;

use App\Repository\DoctorRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/',name:'app_default_home')]
    public function home(DoctorRepository $doctorRepository )
    {
        return $this->render('default/home.html.twig', [

            #Je récupère 3 docteurs au hasard dans la base de donnée
            #Je récupère tous les users dans la base de donnée
            'doctors'=>$doctorRepository->getRandomDoctors(3),

        ]);

    }

}