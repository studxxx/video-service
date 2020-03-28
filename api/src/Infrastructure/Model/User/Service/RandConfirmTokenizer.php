<?php
declare(strict_types=1);

namespace Api\Infrastructure\Model\User\Service;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Service\ConfirmTokenizer;
use DateInterval;
use DateTimeImmutable;

class RandConfirmTokenizer implements ConfirmTokenizer
{
    /** @var DateInterval */
    private $interval;

    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function generate(): ConfirmToken
    {
        return new ConfirmToken(
            (string) random_int(100000, 999999),
            (new DateTimeImmutable())->add($this->interval)
        );
    }
}
