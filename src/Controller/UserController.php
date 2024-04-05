<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Civility;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription.html')]
    public function register(Request $request, EntityManagerInterface $manager): Response

    {
        # Création d'un "user" vide.
        # Il sera rempli par les données entrées par le visiteur
        $user = new User();


        $user->setRoles(['ROLE_USER']);


        #Création du formulaire
        # Passer la requête au formulaire pour traitement
        # Process :
        # 1. Mon utilisateur soumet son formulaire
        # 2. La requête contient les informations soumises via POST
        # 3. Je passe la requête à Symfony (handleRequest)
        # 4. Symfony me retourne mon objet rempli.
        $form = $this->createForm(UserType::class, $user);

        # Passer la requête au formulaire pour traitement
        $form->handleRequest($request);

        # Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $civility=$user->getCivility();
            $address=$user->getAddress();
            $patient=$user->getPatient();


            #Encodage du mot de passe
            # Sauvegarde dans ma BDD


            $manager->persist($address);
            $manager->persist($civility);
            $manager->persist($patient);
            $manager->persist($user);
            $manager->flush();

            # Message de validation
            $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez désormais vous connecter!');

            # Redirection
            return $this->redirectToRoute('app_defaut_home');

        }

        #Passage du formulaire à la vue
        return $this->render('user/register.html.twig', [
            'form' => $form
        ]);

    }

}