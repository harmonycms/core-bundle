<?php

namespace Harmony\Bundle\CoreBundle\Doctrine\Type;

use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Types\Type;
use MyCLabs\Enum\Enum;
use Harmony\Bundle\CoreBundle\Doctrine\Exception\InvalidArgumentException;
use function implode;
use function is_string;
use function is_subclass_of;
use function method_exists;
use function sprintf;

/**
 * Class PhpOdmEnumType
 *
 * @package Harmony\Bundle\CoreBundle\Doctrine\Type
 */
class PhpOdmEnumType extends Type
{

    /** @var string */
    private $name;

    /** @var string */
    protected $enumClass = Enum::class;

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name ?: 'enum';
    }

    /**
     * @param string|null $value
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value)
    {
        if ($value === null) {
            return null;
        }

        // If the enumeration provides a casting method, apply it
        if (method_exists($this->enumClass, 'castValueIn')) {
            /** @var callable $castValueIn */
            $castValueIn = [$this->enumClass, 'castValueIn'];
            $value       = $castValueIn($value);
        }

        // Check if the value is valid for this enumeration
        /** @var callable $isValidCallable */
        $isValidCallable = [$this->enumClass, 'isValid'];
        $isValid         = $isValidCallable($value);
        if (!$isValid) {
            /** @var callable $toArray */
            $toArray = [$this->enumClass, 'toArray'];
            throw new InvalidArgumentException(sprintf('The value "%s" is not valid for the enum "%s". Expected one of ["%s"]',
                $value, $this->enumClass, implode('", "', $toArray())));
        }

        return new $this->enumClass($value);
    }

    /**
     * Converts a value from its PHP representation to its database representation
     * of this type.
     *
     * @param mixed $value The value to convert.
     *
     * @return mixed The database representation of the value.
     */
    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return null;
        }

        // If the enumeration provides a casting method, apply it
        if (method_exists($this->enumClass, 'castValueOut')) {
            /** @var callable $castValueOut */
            $castValueOut = [$this->enumClass, 'castValueOut'];

            return $castValueOut($value);
        }

        // Otherwise, cast to string
        return (string)$value;
    }

    /**
     * @param string      $typeNameOrEnumClass
     * @param string|null $enumClass
     *
     * @throws InvalidArgumentException
     * @throws MongoDBException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public static function registerEnumType($typeNameOrEnumClass, $enumClass = null)
    {
        $typeName  = $typeNameOrEnumClass;
        $enumClass = $enumClass ?: $typeNameOrEnumClass;

        if (!is_subclass_of($enumClass, Enum::class)) {
            throw new InvalidArgumentException(sprintf('Provided enum class "%s" is not valid. Enums must extend "%s"',
                $enumClass, Enum::class));
        }

        // Register and customize the type
        self::addType($typeName, static::class);
        /** @var PhpOdmEnumType $type */
        $type            = self::getType($typeName);
        $type->name      = $typeName;
        $type->enumClass = $enumClass;
    }

    /**
     * @param array $types
     *
     * @throws InvalidArgumentException
     * @throws MongoDBException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public static function registerEnumTypes(array $types)
    {
        foreach ($types as $typeName => $enumClass) {
            $typeName = is_string($typeName) ? $typeName : $enumClass;
            static::registerEnumType($typeName, $enumClass);
        }
    }
}