<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class BigFloatArrayType extends AbstractArrayType
{

    const BIGFLOATARRAY = 'float8[]';

    protected $name = self::BIGFLOATARRAY;

    protected $innerTypeName = 'float8';
}
