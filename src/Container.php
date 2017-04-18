<?php

namespace litepubl\core\container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */

    public function get($id)
    {
        $className = ltrim($id, '\\');
        if ($this->has($className)) {
                return $this->items[$className];
        }

        $result = $this->DI($className);
        $this->items[$className] = $result;
        return $result;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists(ltrim($id, '\\'), $this->items);
    }

    public function DI(string $className)
    {
        $constructArgs = $this->getConstructArgs($className);
        if (!count($constructArgs)) {
            $result = new $className();
        } else {
            $args = [];
            foreach ($constructArgs as $constructArg) {
                if (is_callable($constructArg)) {
                    $args[] = $constructArg();
                } else {
                    $args[] = $constructArg;
                }
            }

                $reflectedClass = new \ReflectionClass($className);
            $result = $reflectedClass->newInstanceArgs($args);
        }

        return $result;
    }

    protected function getConstructArgs(string $className): array
    {
        if (isset($this->constructArgs[$className])) {
            return $this->constructArgs[$className];
        }


        $result = [];
        $reflectedClass = new \ReflectionClass($className);
        if (!$reflectedClass->isInstantiable()) {
            throw new \Exception($className);
        }

        $reflectedConstructor = $reflectedClass->getConstructor();
        if ($reflectedConstructor) {
            $parameters = $reflectedConstructor->getParameters();
            foreach ($parameters as $k => $parameter) {
                $dependency = $parameter->getClass();
                if ($dependency) {
                    $name = $dependency->name;
                    $result[] = function () use ($name) {
                                    return $this->get($name);
                    };
                } elseif ($parameter->isOptional()) {
                                $result[] = $parameter->getDefaultValue();
                } else {
                        throw new InjectionException("Parameter '$parameter->name' must have default value.");
                }
            }
        }

        $this->constructArgs = $result;
        return $result;
    }
}
