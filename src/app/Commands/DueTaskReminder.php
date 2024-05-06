<?php

declare(strict_types=1);

namespace App\Commands;

use App\Entity\Task;
use App\Serilize;
use DateTime;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Request;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:remind')]
class DueTaskReminder extends Command
{
    public function __construct(private readonly EntityManager $entityManager, private readonly RequestFactory $requestFactory)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
            // ->createQueryBuilder('t')
            // ->where('t.user = :id')->setParameter('id', $request->getAttribute('user')->getId());

        $currentTime = (new DateTime(timezone: new \DateTimeZone('africa/lagos')))->format('d/m/Y h:i A');

        foreach ($tasks as $task) {
            if ($task->getDueDate()->format('d/m/Y h:i A') !== $currentTime) {
                echo $task->getDueDate()->format('d/m/Y h:i A') . PHP_EOL;
            } else {
                echo 'none' . PHP_EOL;
                exit;
            }
        }

        var_dump($currentTime);

        return Command::SUCCESS;
    }
}