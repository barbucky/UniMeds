<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        /**
         * 1.Créer un faux client qui se comporte comme un navigateur pointer vers une URL
         * 2.Remplir les champs de mon formulaire d'inscription
         * 3.Est ce que tu peux regarder si dans ma page j'ai le message (alerte) suivant: Votre compte a bien été créé. Vous pouvez désormais vous connecter!
         */


        // 1.
        $client = static::createClient();
        $client->request('GET', '/inscription.html');

        // 2.
        /**
         * civility:user[Civility][sexe]
         * nom:user[last_name]
         * prenom:user[first_name]
         * date de naissance:user[date_Of_Birth]
         * num secu:user[Patient][social_security_number]
         * rue:user[Address][street_name]
         * ville:user[Address][city_name]
         * code postal:user[Address][postal_code]
         * email:user[email]
         * telephone:user[phone]
         * mdp:user[password]
         */
        $client->submitForm('Valider',[
            'user[Civility][sexe]'=>'0',
            'user[last_name]'=>'Priest',
            'user[first_name]'=>'Judith',
            'user[date_Of_Birth]'=>'1963-06-03',
            'user[Patient][social_security_number]'=>'111111111111111',
            'user[Address][street_name]'=>'22 place de la haie',
            'user[Address][city_name]'=>'ROUEN',
            'user[Address][postal_code]'=>'76000',
            'user[email]'=>'mumu@pri.fr',
            'user[phone]'=>'1234567890',
            'user[password]'=>'rtyuiop'
        ]);

        //Follow
        $this->assertResponseRedirects('/connexion.html');
        $client->followRedirect();

        // 3.
        $this->assertSelectorExists('div:contains("Votre compte a bien été créé. Vous pouvez désormais vous connecter!")');

    }
}
