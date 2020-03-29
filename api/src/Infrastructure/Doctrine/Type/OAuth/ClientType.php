<?php
declare(strict_types=1);

namespace Api\Infrastructure\Doctrine\Type\OAuth;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use OAuth2ServerExamples\Entities\ClientEntity;

class ClientType extends StringType
{
    public const NAME = 'oauth_client';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ClientEntity ? $value->getIdentifier() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $client = new ClientEntity($value);
        $client->setName($value);
        return $client;
    }
}
