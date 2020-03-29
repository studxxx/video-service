<?php
declare(strict_types=1);

namespace Api\Test\Feature\Auth;

use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Test\Builder\User\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $user = (new UserBuilder())
            ->withEmail(new Email('test@example.com'))
            ->withDate($now = new \DateTimeImmutable())
            ->withConfirmToken(new ConfirmToken($token = 'token', $now->modify('+1 day')))
            ->build();

        $user->confirmSignup($token, $now);
        $manager->persist($user);
        $manager->flush();
    }
}
