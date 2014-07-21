<?php
namespace SASEdev\Doctrine\DBAL\Pgsql\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 *
 */
class AbstractArrayType extends Type
{

    /**
     *
     * @var string @override
     *
     */
    protected $name;

    /**
     *
     * @var string @override
     *
     */
    protected $innerTypeName;

    /**
     *
     * @var Type
     *
     */
    protected $innerType;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     *
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $innerDeclaration = $this->getInnerType()->getSQLDeclaration($fieldDeclaration, $platform);
        if (substr($innerDeclaration, - 2) == '()') {
            $innerDeclaration = substr($innerDeclaration, 0, - 2);
        }

        return $innerDeclaration . '[]';
    }

    /**
     * @inheritdoc
     */
    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        array_walk_recursive(
            $value,
            array(
                $this,
                'convertToDatabaseCallback'
            ),
            $platform
        );

        return self::parseArrayToPg($value);
    }

    /**
     * @inheritdoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null !== $value) {
            $value = self::parsePgToArray($value);
            array_walk_recursive(
                $value,
                array(
                    $this,
                    'convertToPhpCallback'
                ),
                $platform
            );
        }

        return $value;
    }

    /**
     *
     * @see http://www.php.net/manual/fr/ref.pgsql.php
     *
     * @param string $input
     * @param array $output
     * @param boolean $limit
     * @param integer $offset
     *
     * @return array
     *
     */
    public static function parsePgToArray($input, &$output = null, $limit = false, $offset = 1)
    {
        if (false === $limit) {
            $limit = strlen($input) - 1;
            $output = array();
        }
        if ('{}' != $input) {
            do {
                if ('{' != $input{$offset}) {
                    $matches = array();

                    preg_match("/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/", $input, $matches, 0, $offset);
                    $offset += strlen($matches[0]);
                    $output[] = ('"' != $matches[1]{0} ? $matches[1] : stripcslashes(substr($matches[1], 1, - 1)));

                    if ('},' == $matches[3]) {
                        return $offset;
                    }
                } else {
                    $offset = self::parsePgToArray($input, $output[], $limit, $offset + 1);
                }
            } while ($limit > $offset);
        }

        return $output;
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public static function parseArrayToPg($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::parseArrayToPg($value);
            }
        }

        return '{' . implode(',', $array) . '}';
    }

    /**
     *
     * @return Type
     *
     */
    public function getInnerType()
    {
        if (null === $this->innerType) {
            $this->innerType = Type::getType($this->innerTypeName);
        }

        return $this->innerType;
    }

    /**
     *
     * @param scalar $v
     * @param string $k
     * @param mixed $userData
     *
     */
    protected function convertToPhpCallback(&$v, $k, AbstractPlatform $platform)
    {
        $v = $this->getInnerType()->convertToPHPValue($v, $platform);
    }

    /**
     *
     * @param scalar $v
     * @param string $k
     * @param mixed $userData
     *
     */
    protected function convertToDatabaseCallback(&$v, $k, AbstractPlatform $platform)
    {
        $v = $this->getInnerType()->convertToDatabaseValue($v, $platform);
    }
}
