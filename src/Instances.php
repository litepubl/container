<?php
namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

class Instances implements ContainerInterface
{
    protected $factory;
    protected $DI;
    protected $eventManager;
    protected $items;
    protected $circleNames;

    public function __construct(ContainerInterface $factory, DIInterface $DI, $eventManager)
    {
        $this->factory = $factory;
        $this->DI = $DI;
        $this->eventManager = $eventManager;
        $this->circleNames = [];
        $this->items = [
        get_class($factory) => $factory,
        'factory' => $factory,
            get_class($DI) => $DI,
            'di' => $DI,
            get_class($this) => $this,
        ContainerInterface::class => $this,
            'container' => $this,
        'instances' => $this,
            'services' => $this,
        ];
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            return $this->items[$className];
        }
        
        if (!class_exists($className)) {
            throw new NotFound(sprintf('Class "%s" not found', $className));
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
        if (interface_exists($className)) {
            $result = $this->get($this->getImplementation($className));
        } elseif ($this->factory->has($className)) {
            $factory = $this->get($this->factory->get($className));
            $result = $factory->get($className);
        } else {
            $result = $this->DI->createInstance($className, $this);
        }
        
        return $result;
    }

    public function getImplementation(string $interfaceName): string
    {
        if (isset($this->implementations[$interfaceName])) {
            $result = $this->implementations[$interfaceName];
        } else {
            $a = $this->eventManager->call(
                static::ONINTERFACE, [
                'interfaceName' => $interfaceName,
                'result' => null
                ]
            );
            $result = $a['result'];
            if (!$result) {
                throw new NotFound(sprintf('Interface implementation of "%s" not found', $interfaceName));
            }
        }
        
        return $result;
    }
}
