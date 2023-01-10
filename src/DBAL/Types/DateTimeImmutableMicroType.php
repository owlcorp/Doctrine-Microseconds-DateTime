<?php
declare(strict_types=1);

namespace OwlCorp\DoctrineMicrotime\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

final class DateTimeImmutableMicroType extends BaseDateTimeMicroWithoutTz
{
    const NAME = 'datetime_immutable_micro';

    public function convertToDatabaseValue($phpVal, AbstractPlatform $platform)
    {
        if ($phpVal === null) {
            return $phpVal;
        }

        if ($phpVal instanceof \DateTimeImmutable) {
            return $phpVal->format($this->getDateTimeFormatString($platform));
        }

        throw ConversionException::conversionFailedFormat(
            $phpVal,
            $this->getName(),
            $this->getDateTimeFormatString($platform)
        );
    }

    public function convertToPHPValue($dbVal, AbstractPlatform $platform)
    {
        if ($dbVal === null || $dbVal instanceof \DateTimeImmutable) {
            return $dbVal;
        }

        $phpVal = \DateTimeImmutable::createFromFormat($this->getDateTimeFormatString($platform), $dbVal);
        if ($phpVal !== false) {
            return $phpVal;
        }

        try {
            return new \DateTimeImmutable($dbVal); //it is usually able to guess
        } catch (\Throwable $t) {
            throw ConversionException::conversionFailedFormat(
                $dbVal,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }
    }
}
