<?php

namespace App\Tests;

use App\Entity\FirmwareFileStorage;
use App\Entity\Firmwares;
use PHPUnit\Framework\TestCase;

class FirmwareTest extends TestCase
{
    public function testFirmwareCreation(): void
    {
        $firmware = new Firmwares();
        $firmware->setVersion('1.0.0')
            ->setUploadedAt(new \DateTimeImmutable());

        $this->assertEquals('1.0.0', $firmware->getVersion());
        $this->assertInstanceOf(\DateTimeImmutable::class, $firmware->getUploadedAt());
    }

    public function testFirmwareSetFile(): void
    {
        $firmware = new FirmwareFileStorage();
        $firmware->setFile('12lk3j1l2knm', 'application/x-binary');

        $this->assertEquals('12lk3j1l2knm', $firmware->getFileData());
    }
}
