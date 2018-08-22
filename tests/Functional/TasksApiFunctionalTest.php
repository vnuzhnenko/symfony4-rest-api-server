<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TasksApiFunctionalTest extends WebTestCase
{
    public function testTasksIsAccessible()
    {
        $client = self::createClient();
        $client->request('GET', '/tasks');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}