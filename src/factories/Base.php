<?php
namespace litepubl\core\container\factories;

use Psr\Container\ContainerInterface;

abstract class Base implements FactoryInterface
{
    protected $container;
    protected $classMap = [];
    protected $implementations = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getImplementation(string $className): string
    {
        return $this->implementations[$className] ?? '';
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->classMap[$className])) {
            $method = $this->classMap[$className];
            if ($method && method_exists($this, $method)) {
                        return $this->$method();
            }

            $name = substr($className, strrpos($className, '\\') + 1);
            $method = 'create' . $name;
            if (method_exists($this, $method)) {
                        return $this->$method();
            }

            $method = 'get' . $name;
            if (method_exists($this, $method)) {
                        return $this->$method();
            }
        }
        
        throw new NotFound($className);
    }

    public function has($className)
    {
        return isset($this->classMap[ltrim($className, '\\')]);
    }

    public function set(string $className, string $method)
    {
        $this->classMap[$className] = $method;
    }
}
