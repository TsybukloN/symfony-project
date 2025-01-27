<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setUsername('admin')
            ->setEmail('admin@example.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin_password');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);
        $this->addReference('admin', $admin);

        for ($i = 1; $i <= 5; $i++) {
            $user = new Users();
            $user->setUsername("user{$i}")
                ->setEmail("user{$i}@example.com")
                ->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $this->addReference("user{$i}", $user);
        }

        $manager->flush();
    }
}
