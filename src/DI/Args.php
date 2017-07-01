<?php

namespace LitePubl\Core\Container\DI;

class Args implements ArgsInterface
{
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        return [];
    }

    public function has($className)
    {
        return isset($this->items[$className]);
    }

    public function set(string $className, array $args)
    {
        $this->items[$className] = $args;
    }
}
