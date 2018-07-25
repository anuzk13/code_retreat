<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{

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