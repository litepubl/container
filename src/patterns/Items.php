<?php
namespace litepubl\core\container\patterns;

use Psr\Container\ContainerInterface;
use litepubl\core\container\NotFound;

class Items implements ContainerInterface
{
    protected $items;

    public function __construct(array $items)
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
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function set(string $className, string $factoryClass)
    {
        $this->items[$className] = $factoryClass;
    }
}
