<?php

namespace LitePubl\Container\Factories;

use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Interfaces\FactoryInterface;
use Psr\Container\ContainerInterface;

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

    protected function getFactory($className): FactoryInterface
    {
        if (isset($this->items[$className])) {
            $factoryClass = $this->items[$className];
            return $this->container->get($factoryClass);
        }
        
        throw new NotFound($className);
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        $factory = $this->getFactory($className);
            return $factory->get($className);
    }

    public function has($className)
    {
        return array_key_exists(ltrim($className, '\\'), $this->items);
    }

    public function getImplements(string $className): ? string
    {
                return $this->implementations[$className] ?? null;
    }

    public function getInstaller(string $className): InstallerInterface
    {
        $className = ltrim($className, '\\');
        $factory = $this->getFactory($className);
            return $factory->getInstaller($className);
    }
}
