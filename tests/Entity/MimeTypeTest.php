<?php

namespace App\Tests\Entity;

use App\Entity\FirmwareFileStorage;
use PHPUnit\Framework\TestCase;

class MimeTypeTest extends TestCase
{
    public function testMimeTypeToExtensionMapping(): void
    {
        $tmp_file = new FirmwareFileStorage();

        $this->assertEquals('zip', $tmp_file->setMimeType('application/zip')->getMimeType());
        $this->assertEquals('bin', $tmp_file->setMimeType('application/x-binary')->getMimeType());
        $this->assertEquals(null, $tmp_file->setMimeType('application/pdf')->getMimeType());
    }
}
