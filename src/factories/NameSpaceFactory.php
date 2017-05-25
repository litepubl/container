<?php
namespace litepubl\core\container\factories;

use Psr\Container\ContainerInterface;

class NameSpaceFactory implements FactoryInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getFactoryClass($className): string
    {
        $className = ltrim($className, '\\');
            $ns = substr($className, 0, strrpos($className, '\\'));
        return $ns . '\Factory';
    }

    public function get($className)
    {
        $factoryClass = $this->getFactoryClass($classname);
        $factory = $this->container->get($factoryClass);
        return $factory->get($classname);
    }

    public function has($className)
    {
        $factoryClass = $this->getFactoryClass($className);
        return ($className != $factoryClass) && class_exists($factoryClass);
    }

    public function getImplementation(string $className): string
    {
        return '';
    }

    public function getInstaller(string $className): InstallerInterface
    {
        $factory = $this->get($className);
        return $factory->getInstaller($className);
    }
}
