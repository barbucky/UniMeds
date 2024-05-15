<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Civility;
use App\Entity\Doctor;
use App\Entity\User;
use App\Form\UserPasswordType;
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
        $form->remove('Doctor');

        # Passer la requête au formulaire pour traitement
        $form->handleRequest($request);
        dd($user);


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
            $last_name=strtoupper(addslashes(htmlspecialchars(trim(strip_tags($last_name)))));
            $user->setLastName($last_name);

            $first_name=$user->getFirstName();
            $first_name=ucwords(addslashes(htmlspecialchars(trim(strip_tags($first_name)))),"\ \-");
            $user->setFirstName($first_name);
            $user->setFullName($first_name.' '.$last_name);

            $street_name = $address->getStreetName();
            $street_name= addslashes(htmlspecialchars(trim(strip_tags($street_name))));
            $address->setStreetName($street_name);

            $city_name = $address->getCityName();
            $city_name= addslashes(htmlspecialchars(trim(strip_tags($city_name))));
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
    public function registerDoc(Request $req, UserPasswordHasherInterface $has, EntityManagerInterface $em): Response
    {
        $user = new User();
        $street_name = 'Clinique du renouveau';
        $city_name = 'LOUVROIL';
        $postal_code = '59720';




        $user->setRoles(['ROLE_USER', 'ROLE_DOC']);
        $form = $this->createForm(UserType::class, $user);
        $form->remove('Patient');


        $form->handleRequest($req);


        # Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $civility = $user->getCivility();
            $address = $user->getAddress();
            $doc = $user->getDoctor();



            # Uniformisation de la donnée
            /*
             * Forcer un format identique de données pour limiter les failles dans la BDD
             * Puis->ucwords: Passe la première lettre de chaque mot en maj.
             * addslashes: échappe les caractères interprétables
             * htmlspecialchars: Remplace tous les caractères spéciaux par leur équivalent html
             * trim: supprime les espaces avant et après la donnée
             * strip_tags: Supprime les balises éventuellement intégrées dans la balise
             * */
            $last_name = $user->getLastName();
            $last_name = strtoupper(addslashes(htmlspecialchars(trim(strip_tags($last_name)))));
            $user->setLastName($last_name);

            $first_name = $user->getFirstName();
            $first_name = ucwords(addslashes(htmlspecialchars(trim(strip_tags($first_name)))), "\ \-");
            $user->setFirstName($first_name);
            $user->setFullName($first_name.' '.$last_name);

            $spe = $doc->getSpecialization();
            $doc ->setSpecialization(strtoupper(addslashes(htmlspecialchars(trim(strip_tags($spe))))));

            $address->setStreetName($street_name);
            $address->setCityName($city_name);
            $address->setPostalCode($postal_code);



            $user->setAddress($address);


            #Encodage du mot de passe
            $hashedPassword = $has->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            # Sauvegarde dans ma BDD

            $em->persist($address);
            $em->persist($civility);
            $em->persist($doc);
            $em->persist($user);
            $em->flush();

            # Message de validation
            $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez désormais vous connecter!');

            # Redirection
            return $this->redirectToRoute('app_login');


        }
        #Passage du formulaire à la vue
        return $this->render('user/registerDoc.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/mon_compte/{id}/edit_password.html', name: 'app_editpassword')]
    public function editPassword(User $user, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])
            ) {
                #Hashage du nouveau mot de passe
               $user->setPassword(
                   $hasher->hashPassword($user,$form->getData()['newPassword']
                   )
               );
               #Update user
               $user->setUpdatedAt(new \DateTimeImmutable());
               $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

               $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_default_home');
            } else{
                $this->addFlash('danger','Le mot de passe actuel renseigné est incorrect');
            }
        }

        return $this->render('user/editPassword.html.twig',[
            'form'=>$form
        ]);

    }





}