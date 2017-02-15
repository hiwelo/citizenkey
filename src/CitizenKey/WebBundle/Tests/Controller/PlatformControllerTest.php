<?php

namespace CitizenKey\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlatformControllerTest extends WebTestCase
{
    public function testSelect()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/platform/choice');
    }
}
