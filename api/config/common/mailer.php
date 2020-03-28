<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    Swift_Mailer::class => function (ContainerInterface $container) {
        ['host' => $host, 'port' => $port, 'username' => $username, 'password' => $password, 'encryption' => $encryption] =
            $container->get('config')['mailer'];
        $transport = (new Swift_SmtpTransport($host, $port))
            ->setUsername($username)
            ->setPassword($password)
            ->setEncryption($encryption);
        return new Swift_Mailer($transport);
    },

    'config' => [
        'mailer' => [
            'host' => getenv('API_MAILER_HOST'),
            'port' => getenv('API_MAILER_PORT'),
            'username' => getenv('API_MAILER_USERNAME'),
            'password' => getenv('API_MAILER_PASSWORD'),
            'encryption' => getenv('API_MAILER_ENCRYPTION'),
            'from' => [getenv('API_MAILER_FROM_EMAIL') => 'App'],
        ]
    ],
];
