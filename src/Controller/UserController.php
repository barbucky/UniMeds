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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription.html',name: 'app_inscription')]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response

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
        # Uniformisation de la donnée
            /*
             * Forcer un format identique de données pour limiter les failles dans la BDD
             * Puis->ucwords: Passe la première lettre de chaque mot en maj.
             * addslashes: échappe les caractères interprétables
             * htmlentities: Remplace tous les caractères spéciaux par leur équivalent html
             * trim: supprime les espaces avant et après la donnée
             * strip_tags: Supprime les balises éventuellement intégrées dans la balse
             * */
            $last_name=$user->getLastName();
            $last_name=strtoupper(addslashes(htmlentities(trim(strip_tags($last_name)))));
            $user->setLastName($last_name);

            $first_name=$user->getFirstName();
            $first_name=ucwords(addslashes(htmlentities(trim(strip_tags($first_name)))),"\ \-");
            $user->setFirstName($first_name);

            $street_name = $address->getStreetName();
            $street_name= addslashes(htmlentities(trim(strip_tags($street_name))));
            $address->setStreetName($street_name);

            $city_name = $address->getCityName();
            $city_name= addslashes(htmlentities(trim(strip_tags($city_name))));
            $address->setStreetName($city_name);


            #Encodage du mot de passe
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            # Sauvegarde dans ma BDD

            $manager->persist($address);
            $manager->persist($civility);
            $manager->persist($patient);
            $manager->persist($user);
            $manager->flush();

            # Message de validation
            $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez désormais vous connecter!');

            # Redirection
            return $this->redirectToRoute('app_login');

        }

        #Passage du formulaire à la vue
        return $this->render('user/register.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/inscription/Doc.html', name: 'app_inscription_doc')]
    public function registerDoc(){

    }


}