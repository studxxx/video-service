<?php
declare(strict_types=1);

use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Api\Model\EventDispatcher;
use Psr\Container\ContainerInterface;

return [
    EventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            [
                // add listeners
            ]
        );
    },
];
