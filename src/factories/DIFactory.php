<?php
namespace LitePubl\Core\Container\Factories;

use LitePubl\Core\Container\Interfaces\FactoryInterface;
use LitePubl\Core\Container\Interfaces\DIInterface;
use LitePubl\Core\Container\Interfaces\InstallerInterface;
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

    public function getImplementation(string $className): string
    {
        return '';
    }

    public function getInstaller(string $className): InstallerInterface
    {
        return $this->container->get(InstallerInterface::class);
    }
}
