<?php
namespace LitePubl\Container\Factories;

use LitePubl\Container\Interfaces\FactoryInterface;
use LitePubl\Container\Interfaces\DIInterface;
use Psr\Container\Interfaces\ContainerInterface;

class DI implements FactoryInterface
{
    protected $container;
    protected $DI;

    public function __construct(ContainerInterface $container, DIInterface $DI)
    {
        $this->container = $container;
        $this->DI = $DI;
    }

    public function get($className)
    {
        return $this->DI->createInstance($className, $this->container);
    }

    public function has($className)
    {
        return $this->DI->has($className);
    }

    public function getImplements(string $className): ? string
    {
        return null;
    }
}
