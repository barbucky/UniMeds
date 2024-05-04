<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    #[Route('/mon_compte/Doctor.html', name: 'Doctor')]
    public function doctorHome():Response
    {
        return $this->render('/doctor/doctorhome.html.twig');

    }

}