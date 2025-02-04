<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileDownloadControllerTest extends WebTestCase
{
    public function testDownloadFileNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/download/999');

        $this->assertResponseStatusCodeSame(404);
        $this->assertStringContainsString('File not found', $client->getResponse()->getContent());
    }
}
