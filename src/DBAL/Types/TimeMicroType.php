<?php
declare(strict_types=1);

namespace OwlCorp\DoctrineMicrotime\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use OwlCorp\DoctrineMicrotime\DBAL\Platform\DateTimeFormatTrait;

final class TimeMicroType extends BaseTimeMicro
{
    use DateTimeFormatTrait;

    const NAME = 'time_micro';

    public function convertToDatabaseValue($phpVal, AbstractPlatform $platform)
    {
        if ($phpVal === null) {
            return $phpVal;
        }

        if ($phpVal instanceof \DateTimeInterface) {
            return $phpVal->format($this->getTimeFormatString($platform));
        }

        throw ConversionException::conversionFailedFormat(
            $phpVal,
            $this->getName(),
            $this->getTimeFormatString($platform)
        );
    }

    public function convertToPHPValue($dbVal, AbstractPlatform $platform)
    {
        if ($dbVal === null || $dbVal instanceof \DateTimeInterface) {
            return $dbVal;
        }

        //The "!" forces Y-m-d to be set to beginning of unix epoch
        $phpVal = \DateTime::createFromFormat('!' . $this->getTimeFormatString($platform), $dbVal);
        if ($phpVal !== false) {
            return $phpVal;
        }

        try {
            return new \DateTime($dbVal); //it is usually able to guess
        } catch (\Throwable $t) {
            throw ConversionException::conversionFailedFormat(
                $dbVal,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }
    }
}
