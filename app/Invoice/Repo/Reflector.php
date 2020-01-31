<?php
/**
 * Reflector class get all namespaces and extract all properties for class
 * event if they are protected or private
 * */

namespace Invoice\Repo;

use Exception;
use Invoice\Interfaces\ReflectorStruct;
use ReflectionClass;

class Reflector implements ReflectorStruct
{
    private $ref;
    private $className;

    public function __construct($className)
    {
        try {
            if (!class_exists($className))
                throw new Exception("It seems like your class doesn't exist");

            $this->ref = new ReflectionClass($className);
            $this->className = $className;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get all class properties and create array
     * @return array
     * */
    public function getProperties(): array
    {
        $instance = new $this->className;
        $arr = [];
        foreach ($this->ref->getProperties() as $property) {
            $property->setAccessible(true);
            $arr[$property->getName()] = $property->getValue($instance);
        }
        return $arr;
    }
}