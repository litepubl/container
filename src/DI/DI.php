<?php
namespace LitePubl\Core\Container\DI;

use LitePubl\Core\Container\Interfaces\DIInterface;
use Psr\Container\ContainerInterface;

class DI implements DIInterface, ContainerInterface
{
    protected $args;

    public function __construct(ArgsInterface $args)
    {
        $this->args = $args;
    }

    public function get($className)
    {
        return $this->createInstance($className, $this);
    }

    public function has($className)
    {
        return class_exists($className);
    }

    public function createInstance(string $className, ContainerInterface $container)
    {
        $args = $this->args->get($className, $container);
        if (count($args)) {
            $reflectedClass = new \ReflectionClass($className);
            return $reflectedClass->newInstanceArgs($args);
        } else {
            return new $className();
        }
    }
}
