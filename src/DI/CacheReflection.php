<?php

namespace LitePubl\Core\Container\DI;

use LitePubl\Core\Container\Interfaces\CacheReflectionInterface;
use LitePubl\Core\Container\Exceptions\NotFound;

class CacheReflection implements CacheReflectionInterface
{
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = [];
    }

    public function get(string $className): array
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        throw new NotFound($className);
    }

    public function has(string $className): bool
    {
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function set(string $className, array $args)
    {
        $this->items[$className] = $args;
    }
}
