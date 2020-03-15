<?php
declare(strict_types=1);

use Slim\Container;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/common/*.php')
]);

$config = $aggregator->getMergedConfig();

return new Container($config);
