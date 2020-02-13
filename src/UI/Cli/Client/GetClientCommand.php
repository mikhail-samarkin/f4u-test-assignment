<?php

declare(strict_types=1);

namespace App\UI\Cli\Client;

use App\Application\Query\Client\GetClient\GetClientQuery;
use App\Domain\Client\Client;
use App\Domain\Shared\ValueObject\Address\Address;
use App\UI\Cli\Shared\CastsTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class GetClientCommand extends Command
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
            ->setName('client:get')
            ->addArgument('uuid', InputArgument::REQUIRED, 'Client identificator.')
            ->setDescription('Get client by uuid.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clientId = $this->getStringFromOption($input, 'uuid');

        /**
         * @var Client $client
         */
        $client = $this->handle(new GetClientQuery($clientId));
        $personInformation = $client->person();
        $shippingAddresses = $client->shippingAddresses();
        $defaultShippingAddress = $client->defaultShippingAddress();

        $output->writeln('<info>Information about client: ' . $clientId . '</info>');

        $output->writeln('FirstName: ' . $personInformation->getFirstName()->getValue());
        $output->writeln('LastName: ' . $personInformation->getLastName()->getValue());

        $output->writeln('<info>Available shipping addresses:</info>');

        foreach ($shippingAddresses as $address) {
            $this->writeAddress($output, $address);
        }

        $output->writeln('<info>Default shipping address:</info>');
        if ($defaultShippingAddress instanceof Address) {
            $this->writeAddress($output, $defaultShippingAddress);
        }


        $this->logger->info(__CLASS__ . ' successfully executed at ' . date('Y-m-d H:i:s'));

        return 0;
    }

    private function writeAddress(OutputInterface $output, Address $address): void
    {
        $output->writeln('Country: ' . $address->getCountry()->getValue());
        $output->writeln('City: ' . $address->getCity()->getValue());
        $output->writeln('Street: ' . $address->getStreet()->getValue());
        $output->writeln('ZipCode: ' . $address->getZipCode()->getValue());
        $output->writeln('-------------');
    }
}
