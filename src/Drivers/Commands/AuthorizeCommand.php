<?php

namespace App\Drivers\Commands;

use App\Adapters\Controllers\AuthorizeController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorizeCommand extends Command
{
    protected static $defaultName = 'app:authorize';

    private AuthorizeController $controller;

    public function __construct(AuthorizeController $controller)
    {
        parent::__construct();
        $this->controller = $controller;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $response = $this->controller->authorize($input->getFirstArgument());
//        $output->writeln($response);
        return Command::SUCCESS;
    }
}