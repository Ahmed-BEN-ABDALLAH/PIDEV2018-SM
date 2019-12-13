<?php

namespace BabySitters\BabySittersBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BabysitterControllerTest extends WebTestCase
{
    public function testAjout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'ajoutB');
    }

    public function testAfficherb()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/afficherB');
    }

    public function testModifier()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifier');
    }

}
