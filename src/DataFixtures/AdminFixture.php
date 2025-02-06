<?php

namespace App\DataFixtures;

use App\Entity\Devices;
use App\Entity\Firmwares;
use App\Entity\Projects;
use App\Entity\Users;
use App\Entity\FirmwareFileStorage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin_password');
        $admin->setUsername('admin')
            ->setEmail('admin@example.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setPassword($hashedPassword);
        $manager->persist($admin);

        $device = new Devices();
        $device->setName('Raspberry Pi 4')
            ->setModel('Model B')
            ->setDescription('Powerful single-board computer with ARM processor.');
        $manager->persist($device);

        $firmware_dir = __DIR__ . '/firmwares_files';

        $files = [
            $firmware_dir . '/example.bin',
            $firmware_dir . '/example.hex',
            $firmware_dir . '/example.elf',
        ];

        $files_id = [];

        foreach ($files as $filePath) {
            if (!file_exists($filePath)) {
                continue;
            }

            $fileData = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);

            $firmwareFile = new FirmwareFileStorage();
            $firmwareFile->setFile($fileData, $mimeType);

            $manager->persist($firmwareFile);
            $manager->flush();

            $files_id[] = $firmwareFile->getId();
        }

        if (!empty($files_id)) {
            $firmware1 = new Firmwares();
            $firmware1->setFirmwareFileId($files_id[0])
                ->setVersion('7.1.4')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[0]);
            $manager->persist($firmware1);

            $firmware2 = new Firmwares();
            $firmware2->setFirmwareFileId($files_id[1])
                ->setVersion('8.0.0')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[1]);
            $manager->persist($firmware2);

            $firmware3 = new Firmwares();
            $firmware3->setFirmwareFileId($files_id[2])
                ->setVersion('11.5.0')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[2]);
            $manager->persist($firmware3);

            $manager->flush();

            $project = new Projects();
            $project->setName('Internet Radio');
            $project->setDescription('Simple internet radio player based on RPI4.');
            $project->setDevice($device);
            $project->setUploadedBy($admin);
            $project->setFirmwareIds([
                $firmware1->getId(),
                $firmware2->getId(),
                $firmware3->getId()
            ]);
            $manager->persist($project);

            $manager->flush();
        }
    }
}
