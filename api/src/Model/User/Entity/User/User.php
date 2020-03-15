<?php
declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @var UserId
     * @ORM\Column(type="user_user_id")
     * @ORM\Id()
     */
    private $id;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    /**
     * @var Email
     * @ORM\Column(type="user_user_email")
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string", name="password_hash")
     */
    private $passwordHash;
    /**
     * @var ConfirmToken
     * @ORM\Embedded(class="ConfirmToken", columnPrefix="confirm_token_")
     */
    private $confirmToken;
    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private $status;

    public function __construct(
        UserId $id,
        DateTimeImmutable $date,
        Email $email,
        string $hash,
        ConfirmToken $confirmToken
    ){
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
    }

    public function confirmSignup($token, DateTimeImmutable $date)
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }

        if (!$this->confirmToken->isEqualTo($token)) {
            throw new DomainException('Confirm token is invalid.');
        }

        if ($this->confirmToken->isExpiredTo($date)) {
            throw new DomainException('Confirm token is expired.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
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

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }

    public function checkEmbeds(): void
    {
        if ($this->confirmToken->isEmpty()) {
            $this->confirmToken = null;
        }
    }
}
