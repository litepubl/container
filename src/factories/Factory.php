<?php

namespace LitePubl\Container\Factories;

use LitePubl\Container\Interfaces\FactoryInterface;
use Psr\Container\ContainerInterface;

abstract class Factory implements FactoryInterface
{
    protected $container;
    protected $classMap = [];
    protected $implementations = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getImplements(string $className): ? string
    {
        return $this->implementations[$className] ?? null;
    }

    public function has($className)
    {
        return isset($this->classMap[ltrim($className, '\\')]);
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        $method = $this->getMethod($className);
        if ($method) {
                        return $this->$method();
        }

        throw new NotFound($className);
    }

    protected function getMethod(string $className): ? string
    {
        if (isset($this->classMap[$className])) {
            $result = $this->classMap[$className];
        }

        if (!$result  || !method_exists($this, $result)) {
            $name = substr($className, strrpos($className, '\\') + 1);
            $result = 'create' . $name;
            if (!method_exists($this, $result)) {
                    $result = 'get' . $name;
                if (!method_exists($this, $result)) {
                    return null;
                }
            }
        }

        return $result;
    }
}
