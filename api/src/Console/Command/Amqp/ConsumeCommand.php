<?php
declare(strict_types=1);

namespace Api\Console\Command\Amqp;

use Kafka\Consumer;
use Kafka\ConsumerConfig;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends Command
{
    /** @var LoggerInterface */
    private $logger;
    /** @var string */
    private $brokers;

    public function __construct(LoggerInterface $logger, string $brokers)
    {
        $this->logger = $logger;
        $this->brokers = $brokers;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:demo:consume');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<comment>Consume message</comment>');
        $config = ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList($this->brokers);
        $config->setBrokerVersion('1.1.0');
        $config->setGroupId('demo');
        $config->setTopics(['notifications']);

        $consumer = new Consumer();
        $consumer->setLogger($this->logger);

        $consumer->start(function ($topic, $part, $message) use ($output) {
            $output->writeln(print_r($message, true));
        });

        $output->writeln('<info>Done!</info>');
    }
}
