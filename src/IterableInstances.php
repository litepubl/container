<?php

namespace litepubl\core\container;

class IterableInstances
{
    protected $container;
    protected $generator;

    public function __construct(IterableContainerInterface $container)
    {
        $this->container = $container;
        $this->generator = null;
    }

    public function __call($name, $args)
    {
        $instances = $this->generator ?? $this->container->getInstances();
        foreach ($instances as $instance) {
            call_user_func_array([$instance, $name], $args);
        }

        return $this;
    }

    public function callback(callable $callback): IterableInstances
    {
        $instances = $this->generator ?? $this->container->getInstances();
        foreach ($instances as $instance) {
            $callback($instance);
        }

        return $this;
    }

    public function forInstanceOf(string $className): IterableInstances
    {
        $this->generator = $this->getInstancesOf($className);
        return $this;
    }

    public function getInstancesOf(string $className)
    {
        $instances = $this->container->getInstances();
        foreach ($instances as $instance) {
            if ($instance instanceof $className) {
                yield $instance;
            }
        }
    }
}
