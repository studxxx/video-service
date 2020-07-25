<?php
declare(strict_types=1);

use Api\Console\Command;
use Kafka\ConsumerConfig;
use Kafka\Producer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    Command\Amqp\ConsumeCommand::class => function (ContainerInterface $container) {
        return new Command\Amqp\ConsumeCommand(
            $container->get(LoggerInterface::class),
            $container->get(ConsumerConfig::class)
        );
    },
    Command\Amqp\ProduceCommand::class => function (ContainerInterface $container) {
        return new Command\Amqp\ProduceCommand(
            $container->get(LoggerInterface::class),
            $container->get(Producer::class)
        );
    },
    'config' => [
        'console' => [
            'commands' => [
                Command\Amqp\ConsumeCommand::class,
                Command\Amqp\ProduceCommand::class,
            ],
        ],
    ],
];
