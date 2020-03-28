<?php
declare(strict_types=1);

namespace Api\Model\User\UseCase\SignUp\Confirm;

use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\UserRepository;
use Api\Model\User\Flusher;

class Handler
{
    /** @var UserRepository */
    private $users;
    /** @var Flusher */
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->email));
        $user->confirmSignup($command->token, new \DateTimeImmutable());

        $this->flusher->flush();
    }
}
