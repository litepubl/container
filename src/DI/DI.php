<?php
namespace litepubl\core\container\DI;

use Psr\Container\ContainerInterface;

class DI implements DIInterface, ContainerInterface
{
    const TYPE = 'type';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const CALLBACK = 'callback';
    protected $constructors;

    public function __construct()
    {
    }

    public function get($className)
    {
        return $this->createInstance($className, $this);
    }

    public function has($className)
    {
        return class_exists($className);
    }

    public function createInstance(string $className, ContainerInterface $container)
    {
        $args = $this->getArgs($className, $container);
        if (count($args)) {
            $reflectedClass = new \ReflectionClass($className);
            return $reflectedClass->newInstanceArgs($args);
        } else {
            return new $className();
        }
    }

    public function getArgs(string $className, ContainerInterface $container): array
    {
        $args = $this->getConstructArgs($className);
        $result = [];
        foreach ($args as $arg) {
            $value = $arg[static::VALUE];
            
            switch ($arg[static::TYPE]) {
                case static::CLASS_NAME:
                    $result[] = $container->get($value);
                    break;
                
                case static::CALLBACK:
                    $result[] = call_user_func_array($value, [
                        $className,
                        $container
                    ]);
                    break;
                
                case static::VALUE:
                    $result[] = $value;
                    break;
                
                default:
                    throw new NotFound(sprintf('Unknown "%s" argument type for "%s" in constructor "%s"', $arg[static::TYPE], $arg[static::NAME], $className));
            }
        }
        
        return $result;
    }

    protected function getConstructArgs(string $className): array
    {
        if (isset($this->constructArgs[$className])) {
            return $this->constructArgs[$className];
        }
        
        $reflectedClass = new \ReflectionClass($className);
        if (!$reflectedClass->isInstantiable()) {
            throw new \Exception($className);
        }
        
        $result = [];
        $reflectedConstructor = $reflectedClass->getConstructor();
        if ($reflectedConstructor) {
            $parameters = $reflectedConstructor->getParameters();
            foreach ($parameters as $parameter) {
                $name = $parameter->getName();
                $dependency = $parameter->getClass();
                if ($dependency) {
                    $result[] = [
                        static::TYPE => static::CLASS_NAME,
                        static::NAME => $name,
                        static::VALUE => $dependency->name
                    ];
                } elseif ($parameter->isOptional()) {
                    $result[] = [
                        static::TYPE => static::VALUE,
                        static::NAME => $name,
                        static::VALUE => $parameter->getDefaultValue()
                    ];
                } else {
                    throw new InjectionException("Parameter '$parameter->name' must have default value.");
                }
            }
        }
        
        $this->constructArgs[$className] = $result;
        return $result;
    }
}
