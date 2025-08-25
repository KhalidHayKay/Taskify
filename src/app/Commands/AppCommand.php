<?php

declare(strict_types=1);

namespace App\Commands;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'db:get-name', description: 'prints the app\'s database')]
class AppCommand extends Command
{
    public function __construct(private readonly EntityManager $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        var_dump($this->em->getRepository(User::class)->find(1)->getFullname());

        return Command::SUCCESS;
    }
}