<?php
declare(strict_types=1);

namespace Api\Http\Action\Auth\SignUp;

use Api\Model\User\UseCase\SignUp\Confirm\Command;
use Api\Model\User\UseCase\SignUp\Confirm\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ConfirmAction implements RequestHandlerInterface
{
    /** @var Handler */
    private $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->deserialize($request);

        $this->handler->handle($command);

        return new JsonResponse([]);
    }

    private function deserialize(ServerRequestInterface $request): Command
    {
        $body = $request->getParsedBody();
        $command = new Command();

        $command->email = $body['email'] ?? '';
        $command->token = $body['token'] ?? '';

        return $command;
    }
}
