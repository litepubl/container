<?php

namespace litepubl\core\container\DI;

class Args implements ArgsInterface
{
    protected $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function get(string $className): array
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        return [];
    }

    public function set(string $className, array $args)
    {
        $this->items[$className] = $args;
    }
}
