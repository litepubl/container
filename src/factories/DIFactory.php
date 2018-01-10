<?php
namespace LitePubl\Container\Factories;

use LitePubl\Container\Interfaces\FactoryInterface;
use LitePubl\Container\Interfaces\DIInterface;
use LitePubl\Container\Interfaces\InstallerInterface;
use Psr\Container\Interfaces\ContainerInterface;

class DIFactory implements FactoryInterface
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

    public function getInstaller(string $className): InstallerInterface
    {
        return $this->container->get(InstallerInterface::class);
    }
}
