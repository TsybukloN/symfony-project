<?php

namespace App\DataFixtures;

use App\Entity\Devices;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DevicesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $device1 = new Devices();
        $device1->setName('Arduino UNO')
            ->setModel('REV 3')
            ->setDescription('Most popular Arduino device.');
        $manager->persist($device1);
        $this->addReference('UNO', $device1);

        for ($i = 1; $i <= 5; $i++) {
            $device = new Devices();
            $device->setName("Device {$i}")
                ->setModel("Model Z{$i}")
                ->setDescription("Test device number {$i}.");
            $manager->persist($device);
        }

        $manager->flush();
    }
}
