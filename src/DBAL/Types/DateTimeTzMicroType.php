<?php
declare(strict_types=1);

namespace OwlCorp\DoctrineMicrotime\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

final class DateTimeTzMicroType extends BaseDateTimeMicroWithTz
{
    const NAME = 'datetimetz_micro';

    public function convertToDatabaseValue($phpVal, AbstractPlatform $platform)
    {
        if ($phpVal === null) {
            return $phpVal;
        }

        if ($phpVal instanceof \DateTimeInterface) {
            return $phpVal->format($this->getDateTimeTzFormatString($platform));
        }

        throw ConversionException::conversionFailedFormat(
            $phpVal,
            $this->getName(),
            $this->getDateTimeTzFormatString($platform)
        );
    }

    public function convertToPHPValue($dbVal, AbstractPlatform $platform)
    {
        if ($dbVal === null || $dbVal instanceof \DateTimeInterface) {
            return $dbVal;
        }

        $phpVal = \DateTime::createFromFormat($this->getDateTimeTzFormatString($platform), $dbVal);
        if ($phpVal !== false) {
            return $phpVal;
        }

        try {
            return new \DateTime($dbVal); //it is usually able to guess
        } catch (\Throwable $t) {
            throw ConversionException::conversionFailedFormat(
                $dbVal,
                $this->getName(),
                $platform->getDateTimeTzFormatString()
            );
        }
    }
}
