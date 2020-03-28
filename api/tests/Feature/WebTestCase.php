<?php
declare(strict_types=1);

namespace Api\Test\Feature;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class WebTestCase extends TestCase
{
    protected function get(string $uri): ResponseInterface
    {
        return $this->method($uri, 'GET');
    }

    protected function post(string $uri, array $params = []): ResponseInterface
    {
        return $this->method($uri, 'POST', $params);
    }

    protected function method(string $uri, $method, array $params = []): ResponseInterface
    {
        $body = new Stream('php://temp', 'r+');
        $body->write(json_encode($params));
        $body->rewind();

        return $this->request(
            (new ServerRequest())
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/json')
                ->withUri(new Uri('http://test' . $uri))
                ->withMethod($method)
                ->withBody($body)
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
        $container = $this->container();
        $app = new App($container);
        (require 'config/routes.php')($app, $container);

        return $app;
    }

    protected function container(): ContainerInterface
    {
        return require 'config/container.php';
    }

    protected function loadFixtures(array $fixtures): void
    {
        $container = $this->container();
        $em = $container->get(EntityManagerInterface::class);

        $loader = new Loader();
        foreach ($fixtures as $class) {
            $fixture = $container->has($class) ? $container->get($class) : new $class;
            $loader->addFixture($fixture);
        }

        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }
}
