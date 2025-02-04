<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testFindUserByUsername(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $userRepository = $container->get('doctrine')->getRepository(User::class);

        $user = new User();
        $user->setUsername('testuser');
        $user->setPassword('password123');

        $entityManager = $container->get('doctrine')->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $foundUser = $userRepository->findOneBy(['username' => 'testuser']);

        $this->assertNotNull($foundUser);
        $this->assertEquals('testuser', $foundUser->getUsername());
    }
}
