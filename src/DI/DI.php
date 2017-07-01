<?php
namespace LitePubl\Core\Container\DI;

use Psr\Container\ContainerInterface;

class DI implements DIInterface, ContainerInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const CALLBACK = 'callback';
    const UNDEFINED = 'undefined';

    protected $args;
    protected $cache;

    public function __construct(ArgsInterface $args, CacheInterface $cache)
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
        $args = $this->getConstructorArgs($className);
        $result = [];
        if (count($args)) {
                $namedArgs = $this->args->get($className);
            foreach ($args as $i => $arg) {
                $name = $arg[static::NAME];
                if (isset($namedArgs[$name])) {
                            $value = $namedArgs[$name];
                } elseif (isset($namedArgs[$i])) {
                            $value = $namedArgs[$i];
                } else {
                                $value = $arg[static::VALUE];
                }

                switch ($arg[static::TYPE]) {
                    case static::CLASS_NAME:
                        $result[] = $container->get($value);
                        break;
                
                    case static::CALLBACK:
                        $result[] = call_user_func_array($value, [
                        $container,
                        $className,
                        $name,
                        ]);
                        break;
                
                    case static::VALUE:
                        $result[] = $value;
                        break;

                    case static::UNDEFINED:
                        throw new \InvalidArgumentException(sprintf('Undefined argument "%s" in constructor "%s"', $name, $className));
                    break;
                
                    default:
                        throw new UnknownArgTypeException(sprintf('Unknown "%s" argument type for "%s" in constructor "%s"', arg[static::TYPE], $name, $className));
                }
            }
        }
        
        return $result;
    }

    protected function getConstructorArgs(string $className): array
    {
        if ($this->cache->has($className)) {
            return $this->cache->get($className);
        }
        
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
        
        $this->cache->set($className, $result);
        return $result;
    }
}
