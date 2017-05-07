<?php
namespace litepubl\core\container;

use litepubl\core\container\factories\FactoryInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

class Instances implements ContainerInterface
{
    protected $factory;
    protected $eventManager;
    protected $items;
    protected $circleNames;

    public function __construct(FactoryInterface $factory, EventsInterface $eventManager)
    {
        $this->factory = $factory;
        $this->eventManager = $eventManager;
        $this->circleNames = [];
        $this->items = [
        'factory' => $factory,
        get_class($factory) => $factory,
         get_class($this) => $this,
            ContainerInterface::class => $this,
            PsrContainerInterface::class => $this,
            'container' => $this,
            'instances' => $this,
            'services' => $this,
        'instanceEvents' => $eventManager,
        ];
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        if (!class_exists($className)) {
            throw new NotFound($className);
        }
        
        if (in_array($className, $this->circleNames)) {
            throw new Exception(sprintf('Class "%s" has circle dependencies, current classes stack ', $className, implode("\n", $this->circleNames)));
        }
        
        $this->circleNames[] = $className;
        try {
            $result = $this->createInstance($className);
            $this->items[$className] = $result;
        } finally {
            array_pop($this->circleNames);
        }
        
        return $result;
    }

    public function has($className)
    {
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function set($instance, string $name = '')
    {
        $this->items[get_class($instance)] = $instance;
        if ($name) {
            $name = ltrim($name, '\\');
            $this->items[$name] = $instance;
        }
    }

    public function createInstance(string $className)
    {
        if ($newClass = $this->factory->getImplementation($className)) {
            $result = $this->get($newClass);
        } elseif ($this->factory->has($className)) {
            $result = $this->factory->get($className);
        } else {
                throw new NotFound($className);
        }
        
        return $result;
    }
}
