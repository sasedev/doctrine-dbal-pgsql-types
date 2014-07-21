<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class BigIntArrayType extends AbstractArrayType
{

    const BIGINTARRAY = 'bigint[]';

    protected $name = self::BIGINTARRAY;

    protected $innerTypeName = 'bigint';
}
