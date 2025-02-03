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

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new Users();
        $user->setUsername('user')
            ->setEmail('user@example.com')
            ->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user_password');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $device = new Devices();
        $device->setName('Arduino Nano')
            ->setModel('Old Boot Buffer')
            ->setDescription('Second most popular Arduino device.');
        $manager->persist($device);

        $firmware_dir = __DIR__ . '/firmwares_files';

        $files = [
            $firmware_dir . '/example.bin',
            $firmware_dir . '/example.hex',
            $firmware_dir . '/example.elf',
            $firmware_dir . '/example.so',
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
                ->setVersion('1.0.0')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[0]);
            $manager->persist($firmware1);

            $firmware2 = new Firmwares();
            $firmware2->setFirmwareFileId($files_id[1])
                ->setVersion('1.1.0')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[1]);
            $manager->persist($firmware2);

            $firmware3 = new Firmwares();
            $firmware3->setFirmwareFileId($files_id[2])
                ->setVersion('1.6.0')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[2]);
            $manager->persist($firmware3);

            $firmware4 = new Firmwares();
            $firmware4->setFirmwareFileId($files_id[3])
                ->setVersion('2.1.0')
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($files_id[3]);
            $manager->persist($firmware4);

            $manager->flush();

            $project = new Projects();
            $project->setName('Wormhole');
            $project->setDescription('This project allowed create own mesh-network based on this devices.');
            $project->setDevice($device);
            $project->setUploadedBy($user);
            $project->setFirmwareIds([
                $firmware1->getId(),
                $firmware2->getId(),
                $firmware3->getId(),
                $firmware4->getId()
            ]);
            $manager->persist($project);

            $manager->flush();
        }
    }
}
