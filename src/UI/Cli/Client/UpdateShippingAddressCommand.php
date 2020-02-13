<?php

declare(strict_types=1);

namespace App\UI\Cli\Client;

use App\UI\Cli\Shared\CastsTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Command\Client\UpdateShippingAddress\UpdateShippingAddressCommand as UpdateAddressOnClientCommand;

class UpdateShippingAddressCommand extends Command
{
    use HandleTrait;
    use CastsTrait;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MessageBusInterface $commandBus, LoggerInterface $logger)
    {
        parent::__construct();
        $this->messageBus = $commandBus;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('client:update-shipping-address')
            ->setDescription('Update shipping address on client.')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('id', 'id', InputOption::VALUE_REQUIRED),
                    new InputOption('country', 'country', InputOption::VALUE_REQUIRED),
                    new InputOption('city', 'city', InputOption::VALUE_REQUIRED),
                    new InputOption('street', 'street', InputOption::VALUE_REQUIRED),
                    new InputOption('zipCode', 'zipCode', InputOption::VALUE_REQUIRED),
                    new InputOption('new-country', 'new-country', InputOption::VALUE_REQUIRED),
                    new InputOption('new-city', 'new-city', InputOption::VALUE_REQUIRED),
                    new InputOption('new-street', 'new-street', InputOption::VALUE_REQUIRED),
                    new InputOption('new-zipCode', 'new-zipCode', InputOption::VALUE_REQUIRED),
                ])
            );
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $uuid = $this->getStringFromOption($input, 'id');
        $country = $this->getStringFromOption($input, 'country');
        $city = $this->getStringFromOption($input, 'city');
        $street = $this->getStringFromOption($input, 'street');
        $zipCode = $this->getIntFromOption($input, 'zipCode');
        $newCountry = $this->getStringFromOption($input, 'new-country');
        $newCity = $this->getStringFromOption($input, 'new-city');
        $newStreet = $this->getStringFromOption($input, 'new-street');
        $newZipCode = $this->getIntFromOption($input, 'new-zipCode');

        $this->handle(new UpdateAddressOnClientCommand(
            $uuid,
            $country,
            $city,
            $street,
            $zipCode,
            $newCountry,
            $newCity,
            $newStreet,
            $newZipCode
        ));

        $output->writeln(
            '<info>Shipping address updating on client successfully.</info>'
        );

        $this->logger->info(__CLASS__ . ' successfully executed at ' . date('Y-m-d H:i:s'));

        return 0;
    }
}
