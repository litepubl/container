<?php

namespace litepubl\core\container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected $items;
    protected $DI;

    public function __construct(DI $DI)
    {
        $this->items = [];
        $this->DI = $DI;
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
                return $this->items[$className];
        }

        $result = $this->createInstance($className);
        $this->items[$className] = $result;
        return $result;
    }

    public function has($className)
    {
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function set($instance, string $name = '')
    {
        if ($name) {
            $name = ltrim($name, '\\');
        } else {
            $name = get_class($instance);
        }

        $this->items[$name] = $instance;
    }

    public function createInstance(string $className)
    {
        return $this->DI->createInstance($className, [$this, 'get']);
    }
}
