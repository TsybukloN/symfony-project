<?php

namespace App\DataFixtures;

use App\Entity\Devices;
use App\Entity\Firmwares;
use App\Entity\Projects;
use App\Entity\Users;
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

        $firmware1 = new Firmwares();
        $firmware1->setFirmwareFileId(1)
            ->setVersion('1.0.0')
            ->setUploadedAt(new \DateTimeImmutable());
        $manager->persist($firmware1);

        $firmware2 = new Firmwares();
        $firmware2->setFirmwareFileId(2)
            ->setVersion('1.1.0')
            ->setUploadedAt(new \DateTimeImmutable());
        $manager->persist($firmware2);

        $firmware3 = new Firmwares();
        $firmware3->setFirmwareFileId(2)
            ->setVersion('1.6.0')
            ->setUploadedAt(new \DateTimeImmutable());
        $manager->persist($firmware3);

        $firmware4 = new Firmwares();
        $firmware4->setFirmwareFileId(2)
            ->setVersion('2.1.0')
            ->setUploadedAt(new \DateTimeImmutable());
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
