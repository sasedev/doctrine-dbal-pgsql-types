<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class TextArrayType extends AbstractArrayType
{
    const TEXTARRAY = 'text[]';

    protected $name = self::TEXTARRAY;

    protected $innerTypeName = 'text';
}
