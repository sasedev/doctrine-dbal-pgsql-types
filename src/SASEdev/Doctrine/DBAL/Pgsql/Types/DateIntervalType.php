<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class DateIntervalType extends Type
{

    /**
     *
     * @var string
     */
    const DATEINTERVAL = 'interval';

    /**
     * (non-PHPdoc)
     *
     * @see \Doctrine\DBAL\Types\Type::getName()
     */
    public function getName()
    {
        return self::DATEINTERVAL;
    }



    /**
     * (non-PHPdoc)
     *
     * @see \Doctrine\DBAL\Types\Type::getSQLDeclaration()
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "interval";
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
     */
    public function convertToDatabaseValue ($interval, AbstractPlatform $platform)
    {
        if ($interval === null) {
            return null;
        }

        $sql = "
            $interval->y year +
            $interval->m month +
            $interval->d day +
            $interval->h hour +
            $interval->i minute +
            $interval->s second
        ";

        return $sql;
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

        $matches = array();

        preg_match(
            '`(?:(?P<year>[0-9]+) (?:year|years))? ?(?:(?P<month>[0-9]+) (?:month|mons|mon))? '.
            '?(?:(?P<day>[0-9]+) (?:day|days))? ?(?:(?P<hour>[0-9]{2}):(?P<minute>[0-9]{2}):(?P<second>[0-9]{2}))`',
            $value,
            $matches
        );

        if ($matches['year']) {
            $y = $matches['year'].'Y';
        } else {
            $y = null;
        }
        if ($matches['month']) {
            $m = $matches['month'].'M';
        } else {
            $m = null;
        }
        if ($matches['day']) {
            $d = $matches['day'].'D';
        } else {
            $d = null;
        }

        if ($y !== null || $m !== null || $d !== null) {
            $p = $y.$m.$d;
        } else {
            $p = '0Y0M0D';
        }


        if ($matches['hour']) {
            $h = $matches['hour'].'H';
        } else {
            $h = null;
        }
        if ($matches['minute']) {
            $i = $matches['minute'].'M';
        } else {
            $i = null;
        }
        if ($matches['second']) {
            $s = $matches['second'].'S';
        } else {
            $s = null;
        }

        if ($h !== null || $i !== null || $s !== null) {
            $t = $h.$i.$s;
        } else {
            $t = '0H0M0S';
        }
        $di = new \DateInterval('P'.$p.'T'.$t);

        return $di;
    }
}
