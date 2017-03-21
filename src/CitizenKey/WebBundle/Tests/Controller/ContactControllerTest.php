<?php

namespace CitizenKey\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testDashboard()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contacts');
    }

    public function testCard()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contact');
    }

}
