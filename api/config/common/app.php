<?php
declare(strict_types=1);

use Api\Http\Action;
use Api\Model;
use Api\Http\Middleware;
use Psr\Container\ContainerInterface;

return [
    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },

    Action\Auth\SignUp\RequestAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction(
            $container->get(Model\User\UseCase\SignUp\Request\Handler::class)
        );
    },

    Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },
];
