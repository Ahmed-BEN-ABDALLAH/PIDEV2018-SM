<?php

namespace Sante\SpecialisteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MedecinSpecialisteControllerTest extends WebTestCase
{
    public function testAjouterinfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouterInfo');
    }

    public function testModifierinfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifierInfo');
    }

}
