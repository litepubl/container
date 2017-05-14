<?php
namespace litepubl\core\container;

use litepubl\core\container\factories\FactoryInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

class Container implements ContainerInterface
{
    protected $factory;
    protected $events;
    protected $items;
    protected $circleNames;

    public function __construct(FactoryInterface $factory, EventsInterface $events)
    {
        $this->factory = $factory;
        $this->events = $events;
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
        'instanceEvents' => $events,
        ];
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        $result = $this->events->onBeforeGet($className);
        if (!$result && isset($this->items[$className])) {
            $result = $this->items[$className];
        }
        if (!$result) {
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
        }
 
        $this->events->onAfterGet($className, $result);
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

        $this->events->onSet($instance, $name);
    }

    public function createInstance(string $className)
    {
        $result = $this->events->onBeforeCreate($className);
        if (!$result) {
            if ($newClass = $this->factory->getImplementation($className)) {
                $result = $this->get($newClass);
            } elseif ($this->factory->has($className)) {
                $result = $this->factory->get($className);
            } else {
                $result = $this->events->onNotFound($className);
                if (!$result) {
                                throw new NotFound($className);
                }
            }
        }


        $this->events->onAfterCreate($className, $result);
                return $result;
    }
}
