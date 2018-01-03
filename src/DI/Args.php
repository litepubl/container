<?php
namespace LitePubl\Container\DI;

use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Exceptions\UndefinedArgValue;
use LitePubl\Container\Exceptions\Uninstantiable;
use LitePubl\Container\Interfaces\ArgsInterface;
use LitePubl\Container\Interfaces\CacheReflectionInterface;
use Psr\Container\ContainerInterface;

class Args implements ArgsInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const REQUIRED = 'required';

    protected $config;
    protected $cache;
    protected $classes;

    public function __construct(ContainerInterface $config, CacheReflectionInterface $cache)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->classes = [];
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

            try {
                $this->classes[] = $className;
                        $result = $this->merge($container, $result, $config);
            } finally {
                array_pop($this->classes);
            }
        }

        return $result;
    }

    protected function merge(ContainerInterface $container, array $args, array $config): array
    {
        $result = [];
        foreach ($args as $i => $arg) {
            $name = $arg[static::NAME];

            if (array_key_exists($name, $config)) {
                        $value = $config[$name];
            } elseif (array_key_exists($i, $config)) {
                $value = $config[$i];
            } elseif (array_key_exists(static::VALUE, $arg)) {
                $value = $arg[static::VALUE];
            } else {
                throw new UndefinedArgValue($this->classes[count($this->classes) - 1], $name);
            }

            if ($arg[static::TYPE] == static::CLASS_NAME) {
                $value = $container->get($value);
            }

            $result[] = $value;
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

    public function getReflectedParams(string $className): array
    {
        $reflectedClass = new \ReflectionClass($className);
        if (!$reflectedClass->isInstantiable()) {
            throw new Uninstantiable($className);
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
                } elseif ($parameter->allowsNull()) {
                    $result[] = [
                        static::TYPE => static::VALUE,
                        static::NAME => $name,
                        static::VALUE => null,
                    ];
                } else {
                    $result[] = [
                        static::TYPE => static::REQUIRED,
                        static::NAME => $name,
                    ];
                }
            }
        }
        
        return $result;
    }
}
