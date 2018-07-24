<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class PlayerTest extends WebTestCase
{
    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $purger = new ORMPurger($entityManager);
        $purger->purge();
    }

    public function testSetPlayer()
    {
        $client = static::createClient();
        $client->request('POST', 
        '/player', 
        array(),
        array(),
        array(
            'CONTENT_TYPE' => 'application/json',
            'HTTP_REFERER' => '/foo/bar',
        ),
        '{"name": "Ana"}'
    );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $resp =  json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($resp["token"], "Token is not empty");
    }
}