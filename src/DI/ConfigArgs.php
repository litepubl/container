<?php

namespace LitePubl\Core\Container\DI;

use LitePubl\Core\Container\Interfaces\ArgsInterface;
use LitePubl\Core\Container\Exceptions\NotFound;

class ConfigArgs implements ArgsInterface
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

        throw new NotFound($className);
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
