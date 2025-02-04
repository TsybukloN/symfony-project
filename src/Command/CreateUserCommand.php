<?php

// src/Command/CreateUserCommand.php

namespace App\Command;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:user',
    description: 'Creates a new user in the system',
)]
class CreateUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user in the system')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('email', InputArgument::REQUIRED, 'Role of user: admin or user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');
        $plainPassword = $input->getArgument('password');

        if ($role === 'admin') {
            $role = 'ROLE_ADMIN';
        } elseif ($role === 'user') {
            $role = 'ROLE_USER';
        }

        $user = new Users();
        $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setUsername($username)
            ->setEmail($email)
            ->setRoles([$role])
            ->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User created successfully!');

        return Command::SUCCESS;
    }
}
