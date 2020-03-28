<?php
declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class ConfirmToken
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $expires;

    public function __construct(string $token, DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function isEqualTo($token): bool
    {
        return $this->token === $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isEmpty(): bool
    {
        return empty($this->token);
    }
}
