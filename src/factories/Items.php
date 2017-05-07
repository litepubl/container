<?php
namespace litepubl\core\container\factories;

use Psr\Container\ContainerInterface;
use litepubl\core\container\NotFound;

class Items implements FactoryInterface
{
    protected $container;
    protected $items;
    protected $implementations;

    public function __construct(ContainerInterface $container, array $items, array $implementations)
    {
        $this->container = $container;
        $this->items = $items;
        $this->implementations = $implementations;
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->items[$className])) {
            $factoryClass = $this->items[$className];
            $factory = $this->container->get($factoryClass);
            return $factory->get($className);
        }
        
        throw new NotFound($className);
    }

    public function has($className)
    {
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function getImplementation(string $className): string
    {
        if (isset($this->implementations[$className])) {
                return $this->implementations[$className];
        }

        return '';
    }
}
