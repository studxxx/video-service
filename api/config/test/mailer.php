<?php
declare(strict_types=1);

use Geekdevs\SwiftMailer\Transport\FileTransport;
use Psr\Container\ContainerInterface;

return [
    Swift_Mailer::class => function (ContainerInterface $container) {
        ['local_path' => $localPath] = $container->get('config')['mailer'];
        $transport = new FileTransport(
            new Swift_Events_SimpleEventDispatcher(),
            $localPath
        );
        return new Swift_Mailer($transport);
    },

    'config' => [
        'mailer' => [
            'local_path' => 'var/mail',
        ],
    ],
];
