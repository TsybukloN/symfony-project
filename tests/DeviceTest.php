<?php

namespace App\Tests;

use App\Entity\Devices;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{
    public function testDeviceCreation(): void
    {
        $device = new Devices();
        $device->setName('Raspberry Pi 5')
            ->setModel('Model A')
            ->setDescription('Powerful single-board computer with ARM processor.');

        $this->assertEquals('Raspberry Pi 5', $device->getName());
        $this->assertEquals('Model A', $device->getModel());
        $this->assertEquals('Powerful single-board computer with ARM processor.', $device->getDescription());
    }
}
