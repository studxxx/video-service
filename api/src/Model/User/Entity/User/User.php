<?php
declare(strict_types=1);

namespace Api\Model\User\Entity\User;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /** @var UserId */
    private $id;
    /** @var \DateTimeImmutable */
    private $date;
    /** @var Email */
    private $email;
    /** @var string */
    private $hash;
    /** @var ConfirmToken */
    private $confirmToken;
    /** @var string */
    private $status;

    public function __construct(
        UserId $id,
        \DateTimeImmutable $date,
        Email $email,
        string $hash,
        ConfirmToken $confirmToken
    ){
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->hash = $hash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->hash;
    }

    public function getConfirmToken(): ConfirmToken
    {
        return $this->confirmToken;
    }
}
