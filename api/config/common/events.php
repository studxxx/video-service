<?php
declare(strict_types=1);

use Api\Infrastructure\Model\EventDispatcher\Listener;
use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Api\Model\EventDispatcher;
use Api\Model\User as UserModel;
use Api\Model\Video as VideoModel;
use Kafka\Producer;
use Psr\Container\ContainerInterface;

return [
    EventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            [
                // add listeners
                UserModel\Entity\User\Event\UserCreated::class => [
                    Listener\User\CreatedListener::class,
                ],

                VideoModel\Entity\Video\Event\VideoCreated::class => [
                    Listener\Video\CreatedListener::class,
                ],
            ]
        );
    },

    Listener\User\CreatedListener::class => function (ContainerInterface $container) {
        return new Listener\User\CreatedListener(
            $container->get(Swift_Mailer::class),
            $container->get('config')['mailer']['from']
        );
    },

    Listener\Video\CreatedListener::class => function (ContainerInterface $container) {
        return new Listener\Video\CreatedListener($container->get(Producer::class));
    }
];
