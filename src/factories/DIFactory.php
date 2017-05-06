<?php
namespace litepubl\core\instances\factories;

use litepubl\core\instances\DI\DIInterface;
use Psr\Container\ContainerInterface;

class DI implements FactoryInterface
{
    protected $DI;
    protected $container;

    public function __construct(DIInterface $DI, ContainerInterface $container)
    {
        $this->DI = $DI;
        $this->container = $container;
    }

    public function get($className)
    {
        return $this->DI->createInstance($className, $this->container);
    }

    public function has($className)
    {
        return $this->DI->has($className);
    }

    public function getImplementation(string $className): string
    {
        return '';
    }
}
