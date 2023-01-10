<?php
declare(strict_types=1);

namespace OwlCorp\DoctrineMicrotime\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SQLServer2008Platform;
use Doctrine\DBAL\Types\Type;
use OwlCorp\DoctrineMicrotime\DBAL\Platform\DateTimeFormatTrait;

abstract class BaseDateTimeMicroWithTz extends Type
{
    use DateTimeFormatTrait;

    const NAME = null;

    public function getName()
    {
        return static::NAME;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform instanceof PostgreSqlPlatform || $platform instanceof OraclePlatform) {
            return 'TIMESTAMP(6) WITH TIME ZONE';
        }

        if ($platform instanceof SQLServer2008Platform) {
            return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
        }

        throw new DBALException(
            \sprintf(
                '%s ("%s") type is not supported on "%s" platform',
                $this->getName(),
                static::class,
                \get_class($platform)
            )
        );
    }
}
