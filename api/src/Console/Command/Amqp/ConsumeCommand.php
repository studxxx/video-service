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
    /** @var ConsumerConfig */
    private $config;

    public function __construct(LoggerInterface $logger, ConsumerConfig $config)
    {
        $this->logger = $logger;
        $this->config = $config;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:demo:consume');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<comment>Consume message</comment>');

        $this->config->setGroupId('demo');
        $this->config->setTopics(['notifications']);

        $consumer = new Consumer();
        $consumer->setLogger($this->logger);

        $consumer->start(function ($topic, $part, $message) use ($output) {
            $output->writeln(print_r($message, true));
        });

        $output->writeln('<info>Done!</info>');
    }
}
