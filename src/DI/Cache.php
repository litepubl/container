<?php

namespace LitePubl\Core\Container\DI;

use LitePubl\Core\Container\NotFound;

class Cache implements CacheInterface
{
    protected $items;

    public function __construct()
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

    public function set(string $className, array $args)
    {
        $this->items[$className] = $args;
    }
}
