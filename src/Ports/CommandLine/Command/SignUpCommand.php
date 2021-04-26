<?php

declare(strict_types=1);

namespace BookShop\Ports\CommandLine\Command;

use BookShop\Application\Command\BackOffice\Customer\SignUp;
use BookShop\Application\Command\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SignUpCommand extends Command
{
    public function __construct(private CommandBus $commandBus)
    {
        parent::__construct('book-shop:customer:sign-up');
    }

    protected function configure(): void
    {
        $required = InputOption::VALUE_REQUIRED;

        $this
            ->setDescription('Signs up a new user.')
            ->addArgument(name: 'emailAddress', mode: $required, description: 'Email Address')
            ->addArgument(name: 'password', mode: $required, description: 'Password')
            ->addArgument(name: 'firstName', mode: $required, description: 'First Name')
            ->addArgument(name: 'lastName', mode: $required, description: 'Last Name')
            ->addArgument(name: 'id', mode: InputOption::VALUE_OPTIONAL, description: 'ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $this->commandBus->dispatch(new SignUp(
            $input->getArgument('id') ?: Uuid::uuid4()->toString(),
            $input->getArgument('emailAddress'),
            $input->getArgument('password'),
            $input->getArgument('firstName'),
            $input->getArgument('lastName'),
        ));

        $output->writeln('Customer registered');

        return self::SUCCESS;
    }
}
