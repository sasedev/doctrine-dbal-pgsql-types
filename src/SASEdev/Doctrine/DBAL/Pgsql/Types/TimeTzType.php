<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class TimeTzType extends Type
{

    /**
     *
     * @var string
     */
    const TIME_TZ = 'time_tz';

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::getName()
     */
    public function getName()
    {
        return self::TIME_TZ;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::getSQLDeclaration()
     */
    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('TIME_TZ');
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
     */
    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format('H:i:s.uO') : null;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::convertToPHPValue()
     */
    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        try {
            $val = \DateTime::createFromFormat('H:i:sO', $value);
            if ($val === false) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s');
            }
        } catch (\Exception $e) {
            $val = \DateTime::createFromFormat('H:i:s.uO', $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s.uO');
            }
        }

        return $val;
    }
}
