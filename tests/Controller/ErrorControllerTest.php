<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorControllerTest extends WebTestCase
{
    public function test404ErrorPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/non-existing-route');

        $this->assertResponseStatusCodeSame(404);
        $this->assertStringContainsString('Page not found', $client->getResponse()->getContent());
    }
}
