<?php

namespace App\Drivers\Commands;

use App\Adapters\Gateways\GatewayInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthorizeCommand extends Command
{
    protected static $defaultName = "app:authorize";

    public function __construct(
        private GatewayInterface $gateway
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
        $response = $this->gateway->process($input->getArgument('operations'));
        $output->writeln($response);
        return Command::SUCCESS;
    }
}
