<?php
namespace LitePubl\Core\Container\DI;

use LitePubl\Core\Container\Interfaces\ArgsInterface;
use LitePubl\Core\Container\Interfaces\CacheReflectionInterface;
use LitePubl\Core\Container\Exceptions\NotFound;
use Psr\Container\ContainerInterface;

class Args implements ArgsInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const UNDEFINED = 'undefined';

    protected $config;
    protected $cache;

    public function __construct(ContainerInterface $config, CacheReflectionInterface $cache)
    {
        $this->config = $config;
        $this->cache = $cache;
    }

    public function get(string $className, ContainerInterface $container): array
    {
        $result = $this->getConstructorArguments($className);
        if (count($result)) {
            if ($this->config->has($className)) {
                $config = $this->config->get($className);
            } else {
                $config = [];
            }

            $result = $this->merge($container, $result, $config);
        }

        return $result;
    }

    protected function merge(ContainerInterface $container, array $args, array $config): array
    {
        $result = [];
        foreach ($args as $i => $arg) {
            $name = $arg[static::NAME];
            $value = $config[$name] ?? $config[$i] ?? $arg[static::VALUE] ?? null;
            $result[] = $this->getValue($container, $arg, $value);
        }

        return $result;
    }

    protected function getValue(ContainerInterface $container, array $arg, $value)
    {
        switch ($arg[static::TYPE]) {
            case static::CLASS_NAME:
                $result= $container->get($value);
                break;
                
            case static::VALUE:
                $result = $value;
                break;

            case static::UNDEFINED:
                if ($value) {
                    $result = $value;
                } else {
                    throw new UndefinedArgValueException($name);
                }
                break;
                
            default:
                throw new UnknownArgTypeException($arg[static::NAME], $arg[static::TYPE]);
        }

        return $result;
    }

    protected function getConstructorArguments(string $className): array
    {
        if ($this->cache->has($className)) {
            return $this->cache->get($className);
        }
        
        $result = $this->getReflectedParams($className);
        $this->cache->set($className, $result);
        return $result;
    }

    protected function getReflectedParams(string $className): array
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
