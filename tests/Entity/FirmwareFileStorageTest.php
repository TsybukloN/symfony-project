<?php

namespace App\Tests\Entity;

use App\Entity\FirmwareFileStorage;
use PHPUnit\Framework\TestCase;

class FirmwareFileStorageTest extends TestCase
{
    public function testSetAndGetFileData(): void
    {
        $fileStorage = new FirmwareFileStorage();
        $data = 'binary file content';
        $fileStorage->setFile($data, 'application/x-intel-hex');

        $this->assertEquals($data, $fileStorage->getFileData());
    }
}
