<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\DoctorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/',name:'app_default_home')]
    public function home(DoctorRepository $doctorRepository, Request $request ): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class,$searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $docSearch = $doctorRepository->findBySearch($searchData);

            return $this->render('default/recherche.html.twig',[
                'form'=>$form,
                'docs'=>$docSearch
            ]);
        }
        return $this->render('default/home.html.twig', [

            #Je récupère 3 docteurs au hasard dans la base de donnée
            #Je récupère tous les users dans la base de donnée
            'doctors'=>$doctorRepository->getRandomDoctors(3),
            'form'=>$form->createView()

        ]);

    }


}