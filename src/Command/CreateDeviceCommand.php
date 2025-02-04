<?php

namespace App\Command;

use App\Entity\Devices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'create:device',
    description: 'Creates a new device in the platform',
)]
class CreateDeviceCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new device in the platform')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the device')
            ->addArgument('model', InputArgument::REQUIRED, 'The model of the device')
            ->addArgument('description', InputArgument::OPTIONAL, 'The description of the device')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $model = $input->getArgument('model');
        if ($input->hasArgument('description')) {
            $description = $input->getArgument('description');
        } else {
            $description = null;
        }

        $device = new Devices();
        $device->setName($name)
            ->setModel($model)
            ->setDescription($description);

        $this->entityManager->persist($device);
        $this->entityManager->flush();

        $output->writeln('Device created successfully!');

        return Command::SUCCESS;
    }
}
