<?php
declare(strict_types=1);

use Api\Console\Command;
use Kafka\ConsumerConfig;
use Kafka\Producer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    Command\Kafka\ConsumeCommand::class => function (ContainerInterface $container) {
        return new Command\Kafka\ConsumeCommand(
            $container->get(LoggerInterface::class),
            $container->get(ConsumerConfig::class)
        );
    },
    Command\Kafka\ProduceCommand::class => function (ContainerInterface $container) {
        return new Command\Kafka\ProduceCommand(
            $container->get(LoggerInterface::class),
            $container->get(Producer::class)
        );
    },
    'config' => [
        'console' => [
            'commands' => [
                Command\Kafka\ConsumeCommand::class,
                Command\Kafka\ProduceCommand::class,
            ],
        ],
    ],
];
