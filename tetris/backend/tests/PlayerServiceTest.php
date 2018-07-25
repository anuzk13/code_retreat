<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\PlayerService;

class PlayerServiceTest extends WebTestCase
{

    private $serv;
    private $secret = 'def000004ba8fee5d13ac2b2d8f13d3762bd732df2513df86da00f96da48c36623de3fe1bd45d0e63b82066fe31ccd1f090883d60312c989cd4893797b143c4a33495263';

    public function setUp(){
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $this->serv = new PlayerService($entityManager, $this->secret);
    }

    public function testRegisterPlayer()
    {
        $p = $this->serv->registerPlayer('Ana');
        $this->assertEquals('Ana', $p->getName(), 'right name');
        $this->assertGreaterThan(0, $p->getId(), 'positive id');
    }
}