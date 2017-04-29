<?php
namespace litepubl\core\instances;

class Factory implements FactoryInterface
{
    protected $items;

    public function __construct(array $items, $eventManager)
    {
        $this->items = $items;
        $this->eventManager = $eventManager;
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        throw new NotFound(sprintf('Class %%s not found', $className));
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
