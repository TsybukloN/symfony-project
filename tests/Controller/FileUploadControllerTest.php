<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileUploadControllerTest extends WebTestCase
{
    public function testUploadFile(): void
    {
        $client = static::createClient();
        $client->request('POST', '/upload', [], [
            'file' => new \Symfony\Component\HttpFoundation\File\UploadedFile(
                __DIR__.'/../fixtures/testfile.pdf',
                'testfile.pdf',
                'application/pdf',
                null,
                true
            )
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('File uploaded successfully', $client->getResponse()->getContent());
    }
}
