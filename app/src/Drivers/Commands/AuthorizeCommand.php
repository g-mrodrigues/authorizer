<?php

namespace App\Drivers\Commands;

use App\Adapters\Controller\AuthorizeController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorizeCommand extends Command
{
    protected static $defaultName = "app:authorize";

    public function __construct(
        private AuthorizeController $controller,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Command to authorize operations")
            ->addArgument('operations', InputArgument::REQUIRED, 'Operations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->controller->authorize($input->getArgument('operations'));
        $output->writeln($response);
        return Command::SUCCESS;
    }
}