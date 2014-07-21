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
class DateTimeTzType extends Type
{

    /**
     *
     * @var string
     */
    const DATETIME_TZ = 'timestamp_tz';

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::getName()
     */
    public function getName()
    {
        return self::DATETIME_TZ;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
     */
    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        } elseif ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s.uO');
        } elseif (is_string($value)) {
            try {
                return parent::convertToDatabaseValue($value, $platform);
            } catch (\Exception $e) {
                try {
                    $dt = new \DateTime($value);
                    return $dt->format('Y-m-d H:i:s.uO');
                } catch (\Exception $e) {
                    throw new \Exception('Date "'.$value.'" is not a valid date');
                }
            }
        }

        throw new \Exception('Date "'.$value.'" is not a valid date');
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        try {
            return parent::convertToPHPValue($value, $platform);
        } catch (ConversionException $e) {
            $val = \DateTime::createFromFormat('Y-m-d H:i:s.uO', $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'Y-m-d H:i:s.uO');
            }

            return $val;
        }
    }
}
