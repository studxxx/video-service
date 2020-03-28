<?php
declare(strict_types=1);

namespace Api\Infrastructure\Model\EventDispatcher\Listener\User;

use Api\Model\User\Entity\User\Event\UserCreated;
use Swift_Mailer;
use Swift_Message;

class CreatedListener
{
    /** @var Swift_Mailer */
    private $mailer;
    /** @var array */
    private $from;

    public function __construct(Swift_Mailer $mailer, array $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function __invoke(UserCreated $event): void
    {
        $message = (new Swift_Message('Sign Up Confirmation'))
            ->setFrom($this->from)
            ->setTo($event->email->getEmail())
            ->setBody('Token: ' . $event->confirmToken->getToken());

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}
