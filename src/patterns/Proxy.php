<?php

namespace LitePubl\Container\patterns;

use Psr\Container\ContainerInterface;

class Proxy
{
    protected $instance;
    protected $container;
    protected $name;

    public function __construct(ContainerInterface $container, string $name)
    {
        $this->container = $container;
        $this->name = $name;
    }

    protected function getInstance()
    {
        if (!$this->instance) {
                $this->instance = $this->container->get($this->name);
            $this->container = null;
        }

        return $this->instance;
    }

    public function __call(string $method, array $args)
    {
        return call_user_func_array([$this->getInstance(), $method], $args);
    }

    public function __get(string $name)
    {
        return $this->getInstance()->$name;
    }

    public function __set(string $name, $value)
    {
        $this->getInstance()->$name = $value;
    }
}
