<?php

namespace LitePubl\Container\Factories;

use LitePubl\Container\Interfaces\FactoryInterface;
use Psr\Container\ContainerInterface;

class NameSpaceFactory implements FactoryInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getFactoryClass(string $className): string
    {
        $className = ltrim($className, '\\');
            $ns = substr($className, 0, strrpos($className, '\\'));
        return $ns . '\Factory';
    }

    public function get($className)
    {
        $factoryClass = $this->getFactoryClass($className);
        $factory = $this->container->get($factoryClass);
        return $factory->get($className);
    }

    public function has($className)
    {
        $factoryClass = $this->getFactoryClass($className);
        return ($className != $factoryClass) && class_exists($factoryClass);
    }

    public function getImplements(string $className): ? string
    {
        return null;
    }
}
