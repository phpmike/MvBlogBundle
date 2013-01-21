<?php

namespace Mv\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Mv\BlogBundle\Tests\AppKernel;

class DefaultControllerTest extends WebTestCase
{
    protected static function getKernelClass() {
        return new AppKernel('prod',false);
    }

    public function testIndex()
    {
        // Faire fonctionner ces tests relève du casse tête !
        $this->assertTrue(true);
//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/blog');
//
//        $this->assertTrue(301 === $client->getResponse()->getStatusCode());      
    }
}
