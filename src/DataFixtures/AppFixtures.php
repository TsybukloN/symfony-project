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
        $this->addReference('user', $user);

        $device = new Devices();
        $device->setName('Arduino Nano')
            ->setModel('Old Boot Buffer')
            ->setDescription('Second most popular Arduino device.');
        $manager->persist($device);
        $this->addReference('Nano', $device);

        $firmware1 = new Firmwares();
        $firmware1->setFirmwareFileId(1)
            ->setVersion('1.0.0')
            ->setUploadedAt(new \DateTimeImmutable());
        $manager->persist($firmware1);
        $this->addReference('1.0.0', $firmware1);

        $firmware2 = new Firmwares();
        $firmware2->setFirmwareFileId(2)
            ->setVersion('1.1.0')
            ->setUploadedAt(new \DateTimeImmutable());
        $manager->persist($firmware2);
        $this->addReference('1.1.0', $firmware1);

        $project = new Projects();
        $project->setName('Test Projects');
        $project->setDescription('This is a test project.');
        $project->setDevice($device);
        $project->setUploadedBy($user);
        $project->setFirmwareIds([$firmware1->getId(), $firmware2->getId()]);
        $manager->persist($project);

        $manager->flush();
    }
}
