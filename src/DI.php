<?php

namespace litepubl\core\container;

class DI
{
    const TYPE = 'type';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const CALLBACK = 'callback';

    protected $constructors;

    public function createInstance(string $className, callable $createClass)
    {
        $args = $this->getArgs($className, $createClass);
        if (!count($args)) {
            return new $className();
        }

                $reflectedClass = new \ReflectionClass($className);
        return $reflectedClass->newInstanceArgs($constructorArgs);
    }

    public function getArgs(string $className, callable $createClass): array
    {
        $args = $this->getConstructArgs($className);
            $result = [];
        foreach ($args as $arg) {
            $value = $arg[static::VALUE];

            switch ($arg[static::TYPE]) {
            case static::CLASS_NAME:
                    $result[] = call_user_func_array($createClass, [$value]);
                break;

            case static::CALLBACK:
                    $result[] = call_user_func_array($value, [$this]);
                break;

            case static::VALUE:
                    $result[] = $value;
                break;

            default:
                throw new \Exception();
            }
        }

        return $result;
    }

    protected function getConstructArgs(string $className): array
    {
        if (isset($this->constructArgs[$className])) {
            return $this->constructArgs[$className];
        }

        $reflectedClass = new \ReflectionClass($className);
        if (!$reflectedClass->isInstantiable()) {
            throw new \Exception($className);
        }

        $result = [];
        $reflectedConstructor = $reflectedClass->getConstructor();
        if ($reflectedConstructor) {
            $parameters = $reflectedConstructor->getParameters();
            foreach ($parameters as $parameter) {
                $name = $parameter->getName();
                $dependency = $parameter->getClass();
                if ($dependency) {
                    $result[] = [
                    static::TYPE => static::CLASS_NAME,
                    static::NAME => $name,
                    static::VALUE => $dependency->name,
                    ];
                } elseif ($parameter->isOptional()) {
                                $result[] = [
                    static::TYPE => static::VALUE,
                    static::NAME => $name,
                    static::VALUE => $parameter->getDefaultValue(),
                                ];

                } else {
                        throw new InjectionException("Parameter '$parameter->name' must have default value.");
                }
            }
        }

        $this->constructArgs[$className] = $result;
        return $result;
    }
}
