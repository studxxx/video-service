<?php
declare(strict_types=1);

use Api\Infrastructure\Model\Service\DoctrineFlusher;
use Api\Infrastructure\Model\User\Entity\DoctrineUserRepository;
use Api\Infrastructure\Model\User\Service\BCryptPasswordHasher;
use Api\Infrastructure\Model\User\Service\RandConfirmTokenizer;
use Api\Infrastructure\Model\Video\Entity\DoctrineAuthorRepository;
use Api\Infrastructure\Model\Video\Entity\DoctrineVideoRepository;
use Api\Infrastructure\ReadModel\Video\DoctrineAuthorReadRepository;
use Api\Infrastructure\ReadModel\Video\DoctrineVideoReadRepository;
use Api\Model\EventDispatcher;
use Api\Model\Flusher;
use Api\Model\User as UserModel;
use Api\Model\Video as VideoModel;
use Api\ReadModel;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
    Flusher::class => function (ContainerInterface $container) {
        return new DoctrineFlusher(
            $container->get(EntityManagerInterface::class),
            $container->get(EventDispatcher::class)
        );
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
            $container->get(Flusher::class)
        );
    },

    UserModel\UseCase\SignUp\Confirm\Handler::class => function (ContainerInterface $container) {
        return new UserModel\UseCase\SignUp\Confirm\Handler(
            $container->get(UserModel\Entity\User\UserRepository::class),
            $container->get(Flusher::class),
        );
    },

    ReadModel\User\UserReadRepository::class => function (ContainerInterface $container) {
        return new \Api\Infrastructure\ReadModel\User\DoctrineUserReadRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    VideoModel\UseCase\Author\Create\Handler::class => function (ContainerInterface $container) {
        return new VideoModel\UseCase\Author\Create\Handler(
            $container->get(VideoModel\Entity\Author\AuthorRepository::class),
            $container->get(Flusher::class)
        );
    },

    ReadModel\Video\AuthorReadRepository::class => function (ContainerInterface $container) {
        return new DoctrineAuthorReadRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    ReadModel\Video\VideoReadRepository::class => function (ContainerInterface $container) {
        return new DoctrineVideoReadRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    VideoModel\Entity\Author\AuthorRepository::class => function (ContainerInterface $container) {
        return new DoctrineAuthorRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    VideoModel\Entity\Video\VideoRepository::class => function (ContainerInterface $container) {
        return new DoctrineVideoRepository(
            $container->get(EntityManagerInterface::class)
        );
    },

    VideoModel\UseCase\Video\Create\Handler::class => function (ContainerInterface $container) {
        return new VideoModel\UseCase\Video\Create\Handler(
            $container->get(VideoModel\Entity\Video\VideoRepository::class),
            $container->get(VideoModel\Entity\Author\AuthorRepository::class),
            $container->get(VideoModel\Service\Uploader::class),
            $container->get(VideoModel\Service\Processor\FormatDetector::class),
            $container->get(VideoModel\Service\Processor\Converter\Converter::class),
            $container->get(VideoModel\Service\Processor\Thumbnailer\Thumbnailer::class),
            $container->get(Flusher::class),
            $container->get(VideoModel\UseCase\Video\Create\Preferences::class)
        );
    },

    'config' => [
        'auth' => [
            'signup_confirm_interval' => 'PT5M',
        ],
    ],
];
