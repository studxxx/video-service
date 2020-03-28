<?php

declare(strict_types=1);

namespace Api\Infrastructure\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallableRequestHandler implements RequestHandlerInterface
{
    /** @var callable */
    private $callable;
    /** @var ResponseInterface */
    private $response;

    /**
     * CallableRequestHandler constructor.
     * @param callable $callable
     * @param ResponseInterface $response
     */
    public function __construct(callable $callable, ResponseInterface $response)
    {
        $this->callable = $callable;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        return ($this->callable)($request, $this->response);
    }
}