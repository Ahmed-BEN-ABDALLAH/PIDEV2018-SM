<?php

namespace Sante\articleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class rticleControllerTest extends WebTestCase
{
    public function testListerticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listerticle');
    }

    public function testAjouterarticle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajouterarticle');
    }

}
