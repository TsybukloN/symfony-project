<?php

namespace App\Tests;

use App\Entity\Devices;
use App\Entity\Projects;
use App\Entity\Firmwares;
use App\Entity\Users;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testProjectCreation(): void
    {
        $project = new Projects();
        $project->setName('Internet Radio')
            ->setDescription('Simple internet radio player based on RPI4.');

        $this->assertEquals('Internet Radio', $project->getName());
        $this->assertEquals('Simple internet radio player based on RPI4.', $project->getDescription());
    }

    public function testSetDevice(): void
    {
        $device = new Devices();
        $device->setName('Raspberry Pi 4');

        $project = new Projects();
        $project->setDevice($device);

        $this->assertEquals($device, $project->getDevice());
    }

    public function testUserRoles(): void
    {
        $user = new Users();
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testUserIsAdmin(): void
    {
        $user = new Users();
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertTrue($user->isAdmin());
    }
}
