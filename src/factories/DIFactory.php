<?php
namespace litepubl\core\container\factories;

use litepubl\core\container\DI\DIInterface;
use Psr\Container\ContainerInterface;

class DIFactory implements FactoryInterface
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
