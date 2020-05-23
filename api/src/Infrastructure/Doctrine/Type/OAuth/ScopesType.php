<?php
declare(strict_types=1);

namespace Api\Infrastructure\Doctrine\Type\OAuth;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types;
use OAuth2ServerExamples\Entities\ScopeEntity;

class ScopesType extends Types\JsonType
{
    public const NAME = 'oauth_scopes';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return false|mixed|string|null
     * @throws Types\ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $data = array_map(function (ScopeEntity $entity) {
            return $entity->getIdentifier();
        }, $value);
        return parent::convertToDatabaseValue($data, $platform);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return ScopeEntity[]|array
     * @throws Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = parent::convertToPHPValue($value, $platform);

        if (!$values) {
            return [];
        }
        return array_map(function ($value) {
            return new ScopeEntity($value);
        }, $values);
    }

    public function getName(): string
    {
        return parent::getName();
    }
}
