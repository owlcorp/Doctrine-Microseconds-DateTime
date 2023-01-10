<?php
declare(strict_types=1);

namespace OwlCorp\DoctrineMicrotime\DBAL\Platform;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SQLServer2008Platform;

trait DateTimeFormatTrait
{
    protected function getDateTimeFormatString(AbstractPlatform $platform): string
    {
        if ($platform instanceof PostgreSqlPlatform || $platform instanceof MySqlPlatform ||
            $platform instanceof OraclePlatform || $platform instanceof SQLServer2008Platform) {
            return 'Y-m-d H:i:s.u'; //microseconds (6 digits) precision
        }

        return 'Y-m-d H:i:s.v'; //miliseconds (3 digits) precision
    }

    protected function getDateTimeTzFormatString(AbstractPlatform $platform): string
    {
        if ($platform instanceof PostgreSqlPlatform) {
            return 'Y-m-d H:i:s.uO';
        }

        if ($platform instanceof OraclePlatform) {
            return 'Y-m-d H:i:s.uP';
        }

        if ($platform instanceof SQLServer2008Platform) {
            return 'Y-m-d H:i:s.u P';
        }

        return 'Y-m-d H:i:s.vO';
    }

    protected function getTimeFormatString(AbstractPlatform $platform): string
    {
        if ($platform instanceof PostgreSqlPlatform || $platform instanceof MySqlPlatform ||
            $platform instanceof OraclePlatform || $platform instanceof SQLServer2008Platform) {
            return 'H:i:s.u'; //microseconds (6 digits) precision
        }

        return 'H:i:s.v'; //miliseconds (3 digits) precision
    }
}
