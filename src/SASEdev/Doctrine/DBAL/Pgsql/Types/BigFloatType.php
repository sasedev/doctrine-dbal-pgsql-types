<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class BigFloatType extends Type
{

    /**
     *
     * @var string
     */
    const BIGFLOAT = 'bigfloat';

    /**
     * (non-PHPdoc)
     *
     * @see \Doctrine\DBAL\Types\Type::getName()
     */
    public function getName()
    {
        return self::BIGFLOAT;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Doctrine\DBAL\Types\Type::getSQLDeclaration()
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "float8";
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : (double) $value;
    }
}
