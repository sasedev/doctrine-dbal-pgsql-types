<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class FloatArrayType extends AbstractArrayType
{

    const FLOATARRAY = 'float[]';

    protected $name = self::FLOATARRAY;

    protected $innerTypeName = 'float';
}
