<?php

namespace App\DataFixtures;

use App\Entity\Devices;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DevicesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $platforms = [];

        $device1 = new Devices();
        $device1->setName('Arduino UNO')
            ->setModel('REV 3')
            ->setDescription('Most popular Arduino device.');
        $platforms[] = $device1;

        $device3 = new Devices();
        $device3->setName('ESP32')
            ->setModel('WROOM-32')
            ->setDescription('Wi-Fi & Bluetooth microcontroller with low power consumption.');
        $platforms[] = $device3;

        $device4 = new Devices();
        $device4->setName('BeagleBone Black')
            ->setModel('Rev C')
            ->setDescription('Open-source hardware computer for developers.');
        $platforms[] = $device4;

        $device5 = new Devices();
        $device5->setName('STM32 Nucleo')
            ->setModel('F446RE')
            ->setDescription('Development board for ARM Cortex-M based microcontrollers.');
        $platforms[] = $device5;

        foreach ($platforms as $device) {
            $manager->persist($device);
        }

        $manager->flush();
    }
}
