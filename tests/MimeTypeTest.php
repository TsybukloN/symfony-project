<?php

namespace App\Tests;

use App\Entity\FirmwareFileStorage;
use PHPUnit\Framework\TestCase;

class MimeTypeTest extends TestCase
{
    public function testMimeTypeToExtensionMapping(): void
    {
        $tmp_file = new FirmwareFileStorage();

        $tmp_file->setMimeType('application/zip');
        $this->assertEquals('zip', $tmp_file->getExtension());

        $tmp_file->setMimeType('application/x-binary');
        $this->assertEquals('bin', $tmp_file->getExtension());
    }
}
