<?php
namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

class BaseFactory implements ContainerInterface
{
    protected $container;
    protected $classMap;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->classMap = $this->getClassMap();
    }

    protected function getClassMap(): array
    {
        return [];
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->classMap[$className])) {
            $method = $this->classMap[$className];
            if (method_exists($this, $method)) {
                        return $this->$method();
            }
        }
        
        throw new NotFound($classname);
    }

    public function has($className)
    {
        return isset($this->classMap[ltrim($className, '\\')]);
    }

    public function set(string $className, string $factoryClass)
    {
        $this->classMap[$className] = $factoryClass;
    }
}
