<?php

namespace Api\Test\Unit\Http\Action;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class HomeActionFunctionalTest extends TestCase
{
    public function testSuccess(): void
    {
        $container = require 'config/container.php';
        $app = new App($container);
        (require 'config/routes.php')($app);
        $request = new ServerRequest();
        $response = $app->process($request, new Response());

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = \json_decode($content, true);

        self::assertEquals([
            'name' => 'App API',
            'version' => '1.0',
        ], $data);
    }
}
