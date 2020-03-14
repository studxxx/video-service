<?php
declare(strict_types=1);

use Slim\Container;

$config = require __DIR__ . '/config.php';

return new Container($config);