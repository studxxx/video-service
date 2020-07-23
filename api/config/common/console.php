<?php
declare(strict_types=1);

use Api\Console\Command;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    Command\Amqp\ConsumeCommand::class => function (ContainerInterface $container) {
        $brokers = $container->get('config')['console']['brokers'];
        return new Command\Amqp\ConsumeCommand($container->get(LoggerInterface::class), $brokers);
    },
    Command\Amqp\ProduceCommand::class => function (ContainerInterface $container) {
        $brokers = $container->get('config')['console']['brokers'];
        return new Command\Amqp\ProduceCommand($container->get(LoggerInterface::class), $brokers);
    },
    'config' => [
        'console' => [
            'commands' => [
                Command\Amqp\ConsumeCommand::class,
                Command\Amqp\ProduceCommand::class,
            ],
            'brokers' => 'kafka:9092'
        ],
    ],
];
