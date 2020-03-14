<?php

namespace Api\Test\Unit\Http\Action;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class HomeActionFunctionalTest extends TestCase
{
    public function testSuccess(): void
    {
        $response = $this->get('/');

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = \json_decode($content, true);

        self::assertEquals([
            'name' => 'App API',
            'version' => '1.0',
        ], $data);
    }

    protected function get(string $uri): ResponseInterface
    {
        return $this->method($uri, 'GET');
    }

    protected function method(string $uri, $method): ResponseInterface
    {
        return $this->request(
            (new ServerRequest())
                ->withUri(new Uri('http://test' . $uri))
                ->withMethod($method)
        );
    }

    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->process($request, new Response());
        $response->getBody()->rewind();

        return $response;
    }

    protected function app(): App
    {
        $app = new App($this->container());
        (require 'config/routes.php')($app);

        return $app;
    }

    protected function container(): ContainerInterface
    {
        return require 'config/container.php';
    }
}
