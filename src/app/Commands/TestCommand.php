<?php

declare(strict_types=1);

namespace App\Commands;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'db:get-name', description: 'prints the app\'s database')]
class TestCommand extends Command
{
    Public function __construct(private readonly EntityManager $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write("Database Name: " . $this->em->getConnection()->getDatabase(), true);

        return Command::SUCCESS;
    }
}