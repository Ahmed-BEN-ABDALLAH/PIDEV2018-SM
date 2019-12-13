<?php

namespace JardinDenfant\ProfilJDBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserEventsControllerTest extends WebTestCase
{
    public function testInscrirer()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'inscri');
    }

}
