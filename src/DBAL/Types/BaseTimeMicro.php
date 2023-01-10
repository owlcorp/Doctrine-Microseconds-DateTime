<?php
declare(strict_types=1);

namespace OwlCorp\DoctrineMicrotime\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;
use OwlCorp\DoctrineMicrotime\DBAL\Platform\DateTimeFormatTrait;

abstract class BaseTimeMicro extends Type
{
    use DateTimeFormatTrait;

    const NAME = null;

    public function getName()
    {
        return static::NAME;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform instanceof PostgreSqlPlatform) {
            return 'TIME(6) WITHOUT TIME ZONE';
        }

        if ($platform instanceof MySqlPlatform) {
            return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration) . '(6)';
        }

        if ($platform instanceof OraclePlatform) {
            return 'TIMESTAMP(6)'; //there's no way to save just time
        }

        if ($platform instanceof SqlitePlatform || $platform instanceof SQLServerPlatform) {
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
