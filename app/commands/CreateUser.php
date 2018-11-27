<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Create a new user')
            ->setHelp('This command allows you to create a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
