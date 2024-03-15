<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription.html')]
    public function register()
    {
        # Création d'un "user" vide.
        # Il sera rempli par les données entrées par le visiteur
        $user = new  User();

        #Création du formulaire
        # Passer la requête au formulaire pour traitement
        # Process :
        # 1. Mon utilisateur soumet son formulaire
        # 2. La requête contient les informations soumises via POST
        # 3. Je passe la requête à Symfony (handleRequest)
        # 4. Symfony me retourne mon objet rempli.
        $form = $this->createForm(UserType::class, $user);

        #Passage du formulaire à la vue
        return $this->render('user/register.html.twig', [
            'form' => $form
        ]);

    }

}