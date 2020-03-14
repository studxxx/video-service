<?php

declare(strict_types=1);

use Api\Http\Action;
use Slim\{App, Container};
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

$config = require 'config/config.php';
$container = new Container($config);
$app = new App($container);

(require 'config/routes.php')($app);

$app->run();
