<?php

namespace litepubl\core\container\DI;

class ConstructorArguments implements ConstructorArgumentsInterface
{
    protected $items;

    public function __construct(ContainerInterface $container, array $items, array $implementations)
    {
        $this->container = $container;
        $this->items = $items;
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        throw new NotFound($className);
    }

    public function has($className)
    {
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function set(string $className, array $args)
    {
        $this->items[$className] = $args;
    }
}
