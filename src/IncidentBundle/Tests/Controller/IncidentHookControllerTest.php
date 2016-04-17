<?php

namespace IncidentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IncidentHookControllerTest
 * @package IncidentBundle\Tests\Controller
 */
class IncidentHookControllerTest extends WebTestCase
{
    public function testGetAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/incident/');
    }
}
