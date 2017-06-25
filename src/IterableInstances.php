<?php

namespace LitePubl\Core\Container;

class IterableInstances implements IterableContainerInterface
{
    protected $container;
    protected $condition;
    protected $callback;

    public function __construct(IterableContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setCondition(callable $condition): IterableInstances
    {
        $this->condition  = $condition;
        return $this;
    }

    public function setCallback(callable $callback): IterableInstances
    {
        $this->callback = $callback;
        return $this;
    }

    public function getInstances()
    {
        return $this->extractInstances($this->container->getInstances());
    }

    protected function extractInstances($instances)
    {
        $condition = $this->condition;
        foreach ($instances as $instance) {
            if (!$condition || $condition($instance)) {
                yield $instance;
            }

            if ($instance instanceof IterableContainerInterface) {
                $instances2 = $this->extractInstances($instance->getInstances());
                foreach ($instances2 as $instance2) {
                    if (!$condition || $condition($instance2)) {
                        yield $instance2;
                    }
                }
            }
        }
    }

    public function __call($name, $args)
    {
        $callback = $this->callback;
        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            $result = call_user_func_array([$instance, $name], $args);
            if ($callback) {
                        $callback($instance, $result);
            }
        }

        return $this;
    }

    public function callback(callable $callback): IterableInstances
    {
        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            $callback($instance);
        }

        return $this;
    }

    public function forClass(string $className): IterableInstances
    {
        $this->condition = function ($instance) use ($className) {
                return $instance instanceof $className;
        };

        return $this;
    }
}
