<?php
declare(strict_types=1);

namespace Api\Test\Feature\Auth;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Test\Builder\User\UserBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ConfirmFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $user = (new UserBuilder())
            ->withDate($now = new DateTimeImmutable())
            ->withEmail(new Email('confirm@example.com'))
            ->withConfirmToken(new ConfirmToken('token', $now->modify('+1 day')))
            ->build();

        $manager->persist($user);

        $expired = (new UserBuilder())
            ->withDate($now = new DateTimeImmutable())
            ->withEmail(new Email('expired@example.com'))
            ->withConfirmToken(new ConfirmToken('token', $now->modify('-1 day')))
            ->build();

        $manager->persist($expired);
        $manager->flush();
    }
}
