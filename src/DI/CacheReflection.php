<?php

namespace LitePubl\Container\DI;

use LitePubl\Container\Interfaces\ArrayContainerInterface;
use LitePubl\Container\Exceptions\NotFound;

class CacheReflection implements ArrayContainerInterface
{
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = [];
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

    public function set(string $className, array $args): void
    {
        $this->items[$className] = $args;
    }
}
