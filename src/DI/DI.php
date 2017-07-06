<?php
namespace LitePubl\Core\Container\DI;

use Psr\Container\ContainerInterface;

class DI implements DIInterface, ContainerInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const UNDEFINED = 'undefined';

    protected $args;
    protected $cache;

    public function __construct(ArgsInterface $args, ArgsInterface $cache)
    {
        $this->args = $args;
        $this->cache = $cache;
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
        $args = $this->getCachedArgs($className);
        if (count($args)) {
                $namedArgs = $this->args->get($className);
            return $this->mergeArgs($container, $args, $namedArgs);
        }

        return [];
    }

    protected function mergeArgs(ContainerInterface $container, array $args, array $namedArgs): array
    {
        $result = [];
        foreach ($args as $i => $arg) {
            $name = $arg[static::NAME];
            if (array_key_exists($name, $namedArgs)) {
                        $value = $namedArgs[$name];
                if ($arg[static::TYPE] == static::UNDEFINED) {
                                    $arg[static::TYPE] = static::VALUE;
                }
            } elseif (array_key_exists($i, $namedArgs)) {
                    $value = $namedArgs[$i];
                if ($arg[static::TYPE] == static::UNDEFINED) {
                                    $arg[static::TYPE] = static::VALUE;
                }
            } else {
                        $value = $arg[static::VALUE];
            }

            switch ($arg[static::TYPE]) {
                case static::CLASS_NAME:
                    $result[] = $container->get($value);
                    break;
                
                case static::VALUE:
                    $result[] = $value;
                    break;

                case static::UNDEFINED:
                    throw new UndefinedArgValueException($className, $name);
                break;
                
                default:
                    throw new UnknownArgTypeException($className, $name, $arg[static::TYPE]);
            }
        }
      
        return $result;
    }

    protected function getCachedArgs(string $className): array
    {
        if ($this->cache->has($className)) {
            return $this->cache->get($className);
        }
        
        $result = $this->getConstructorArgs($className);
        $this->cache->set($className, $result);
        return $result;
    }

    protected function getConstructorArgs(string $className): array
    {
        $reflectedClass = new \ReflectionClass($className);
        if (!$reflectedClass->isInstantiable()) {
            throw new UninstantiableException($className);
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
                    $result[] = [
                        static::TYPE => static::UNDEFINED,
                        static::NAME => $name,
                    ];
                }
            }
        }
        
        return $result;
    }
}
