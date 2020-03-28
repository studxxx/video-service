<?php
declare(strict_types=1);

use Api\Infrastructure\Model\Service\DoctrineFlusher;
use Api\Infrastructure\Model\User\Entity\DoctrineUserRepository;
use Api\Infrastructure\Model\User\Service\BCryptPasswordHasher;
use Api\Infrastructure\Model\User\Service\RandConfirmTokenizer;
use Api\Model\EventDispatcher;
use Api\Model\Flusher;
use Api\Model\User as UserModel;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    Flusher::class => function (ContainerInterface $container) {
        return new DoctrineFlusher($container->get(EntityManagerInterface::class));
    },

    UserModel\Service\PasswordHasher::class => function () {
        return new BCryptPasswordHasher();
    },

    UserModel\Entity\User\UserRepository::class => function (ContainerInterface $container) {
        return new DoctrineUserRepository($container->get(EntityManagerInterface::class));
    },

    UserModel\Service\ConfirmTokenizer::class => function (ContainerInterface $container) {
        $interval = $container->get('config')['auth']['signup_confirm_interval'];
        return new RandConfirmTokenizer(new DateInterval($interval));
    },

    UserModel\UseCase\SignUp\Request\Handler::class => function (ContainerInterface $container) {
        return new UserModel\UseCase\SignUp\Request\Handler(
            $container->get(UserModel\Entity\User\UserRepository::class),
            $container->get(UserModel\Service\PasswordHasher::class),
            $container->get(UserModel\Service\ConfirmTokenizer::class),
            $container->get(Flusher::class),
            $container->get(EventDispatcher::class)
        );
    },

    UserModel\UseCase\SignUp\Confirm\Handler::class => function (ContainerInterface $container) {
        return new UserModel\UseCase\SignUp\Confirm\Handler(
            $container->get(UserModel\Entity\User\UserRepository::class),
            $container->get(Flusher::class),
        );
    },

    'config' => [
        'auth' => [
            'signup_confirm_interval' => 'PT5M',
        ],
    ],
];
