<?php declare(strict_types=1);

namespace olml89\PlayaMedia\User\Infrastructure\Persistence;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use olml89\PlayaMedia\User\Domain\UserType;

final class UserTypeType extends Type
{
    private const NAME = 'user_type';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): UserType
    {
        if (!is_int($value)) {
            throw ConversionException::conversionFailed($value, UserType::class);
        }

        return UserType::from($value);
    }
}
