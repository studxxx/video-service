<?php
declare(strict_types=1);

namespace Api\Test\Feature\Auth\OAuth;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Test\Builder\User\UserBuilder;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class AuthFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $user = (new UserBuilder())
            ->withDate($now = new DateTimeImmutable())
            ->withEmail(new Email('oauth@example.com'))
            ->withPasswordHash('$2y$12$ipWPucUNIWqzsGlXoWYyrOFbN7jmYBGXIyhjtuF10ZsPdqGaViJKi') // password
            ->withConfirmToken(new ConfirmToken($token = 'token', $now->modify('+1 day')))
            ->build();

        $user->confirmSignup($token, new DateTimeImmutable());

        $manager->persist($user);
        $manager->flush();
    }
}