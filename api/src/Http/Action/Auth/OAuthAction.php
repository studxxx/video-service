<?php

namespace Api\Http\Action\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response;

class OAuthAction implements RequestHandlerInterface
{
    /** @var AuthorizationServer */
    private $server;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(AuthorizationServer $server, LoggerInterface $logger)
    {
        $this->server = $server;
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->server->respondToAccessTokenRequest($request, new Response());
        } catch (OAuthServerException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            return $e->generateHttpResponse(new Response());
        } catch (\Exception $e) {
            return (new OAuthServerException($e->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse(new Response());
        }
    }
}
