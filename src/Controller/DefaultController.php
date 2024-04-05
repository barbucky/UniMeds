<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/',name:'app_default_home')]
    #[Route()]
    public function home()
    {
        return $this->render('default/home.html.twig');
    }

}