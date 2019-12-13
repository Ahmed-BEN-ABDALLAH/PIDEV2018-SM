<?php

namespace JardinDenfant\ProfilJDBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    public function testAjoute()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajoutE');
    }

    public function testModife()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifE');
    }

    public function testSupprimee()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprimeE');
    }

    public function testRecherchee()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rechercheE');
    }

    public function testAffichere()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherE');
    }

}
