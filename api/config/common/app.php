<?php
declare(strict_types=1);

use Api\Http\Action;
use Api\Http\Validator\Validator as HttpValidator;
use Api\Model;
use Api\Http\Middleware;
use Api\ReadModel;
use Doctrine\Common\Annotations\AnnotationRegistry;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator;

return [
    Validator\Validator\ValidatorInterface::class => function () {
        AnnotationRegistry::registerLoader('class_exists');
        return Validator\Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    HttpValidator::class => function (ContainerInterface $container) {
        return new HttpValidator($container->get(Validator\Validator\ValidatorInterface::class));
    },

    Middleware\BodyParamsMiddleware::class => function () {
        return new Middleware\BodyParamsMiddleware();
    },

    Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },

    Middleware\ValidationExceptionMiddleware::class => function () {
        return new Middleware\ValidationExceptionMiddleware();
    },

    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },

    Action\Auth\SignUp\RequestAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction(
            $container->get(Model\User\UseCase\SignUp\Request\Handler::class),
            $container->get(Api\Http\Validator\Validator::class)
        );
    },

    Action\Auth\SignUp\ConfirmAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\ConfirmAction(
            $container->get(Model\User\UseCase\SignUp\Confirm\Handler::class),
        );
    },

    Action\Auth\OAuthAction::class => function (ContainerInterface $container) {
        return new Action\Auth\OAuthAction(
            $container->get(AuthorizationServer::class),
            $container->get(LoggerInterface::class)
        );
    },

    Action\Profile\ShowAction::class => function (ContainerInterface $container) {
        return new Action\Profile\ShowAction(
            $container->get(ReadModel\User\UserReadRepository::class)
        );
    },

    Action\Author\ShowAction::class => function (ContainerInterface $container) {
        return new Action\Author\ShowAction(
            $container->get(ReadModel\Video\AuthorReadRepository::class)
        );
    },

    Action\Author\CreateAction::class => function (ContainerInterface $container) {
        return new Action\Author\CreateAction(
            $container->get(Model\Video\UseCase\Author\Create\Handler::class),
            $container->get(HttpValidator::class)
        );
    }
];
