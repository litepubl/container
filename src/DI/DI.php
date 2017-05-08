<?php
namespace litepubl\core\container\DI;

use Psr\Container\ContainerInterface;

class DI implements DIInterface, ContainerInterface
{
    const TYPE = 'type';
    const NAME = 'name';
    const VALUE = 'value';
    const CLASS_NAME = 'classname';
    const CALLBACK = 'callback';

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
            foreach ($args as $arg) {
                $name = $arg[static::NAME];
                if (isset($namedArgs[$name])) {
                            $arg = $namedArgs[$name];
                }

                $value = $arg[static::VALUE];
                switch ($arg[static::TYPE]) {
                    case static::CLASS_NAME:
                        $result[] = $container->get($value);
                        break;
                
                    case static::CALLBACK:
                        $result[] = call_user_func_array($value, [
                        $container,
                        $className,
                        ]);
                        break;
                
                    case static::VALUE:
                        $result[] = $value;
                        break;
                
                    default:
                        throw new NotFound(sprintf('Unknown "%s" argument type for "%s" in constructor "%s"', $arg[static::TYPE], $arg[static::NAME], $className));
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
        
        $this->cache->set($className, $result);
        return $result;
    }
}
