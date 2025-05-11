<?php

namespace App\Helpers;

use Exception;
use ReflectionClass;
use ReflectionException;

class ClassHelper
{
    /**
     * @param string|null $object
     * @param string $traitClass
     * @return void
     * @throws Exception
     */
    public static function checkUseTrait(?string $object, string $traitClass): void
    {
        if ($object !== null && !self::isCheckUseTrait($object, $traitClass)) {
            throw new Exception('Класс ' . $object . ' не использует трейт ' . $traitClass);
        }
    }

    /**
     * @param string $class
     * @param string $traitClass
     * @return bool
     */
    public static function isCheckUseTrait(string $class, string $traitClass): bool
    {
        return in_array($traitClass, self::classUsesRecursive($class), true);
    }

    /**
     * @param object $object
     * @param string $interface
     * @return bool
     */
    public static function isInterfaceExist(object $object, string $interface): bool
    {
        return isset(class_implements($object)[$interface]);
    }

    /**
     * @param object $object
     * @param string $interface
     * @return void
     * @throws Exception
     */
    public static function checkInterfaceExist(object $object, string $interface): void
    {
        if (!self::isInterfaceExist($object, $interface)) {
            throw new Exception('Класс ' . get_class($object) . ' не имплементируют интерфейс ' . $interface);
        }
    }

    /**
     * @param object|string $class
     * @param string $name
     * @return bool
     */
    public static function constantExists(object|string $class, string $name): bool
    {
        if (is_string($class)) {
            return defined("$class::$name");
        } else if (is_object($class)) {
            return defined(get_class($class) . "::$name");
        }
        return false;
    }

    /**
     * @param object|string $class
     * @param string $attribute
     * @return bool
     * @throws ReflectionException
     */
    public static function checkIfClassUseAttribute(object|string $class, string $attribute): bool
    {
        $reflectionClass = new ReflectionClass($class);
        return $reflectionClass->getAttributes($attribute) !== [];
    }

    /**
     * @param string $class
     * @return array
     */
    public static function classUsesRecursive(string $class): array
    {
        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += self::traitUsesRecursive($class);
        }

        return array_unique($results);
    }

    /**
     * @param string $trait
     * @return array
     */
    private static function traitUsesRecursive(string $trait): array
    {
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $trait) {
            $traits += self::traitUsesRecursive($trait);
        }

        return $traits;
    }
}