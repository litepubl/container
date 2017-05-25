<?php
namespace litepubl\core\container\factories;

use litepubl\core\container\DI\DIInterface;
use Psr\Container\ContainerInterface;

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
