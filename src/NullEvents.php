<?php

namespace LitePubl\Core\Container;

class NullEvents implements EventsInterface
{

    public function setContainer(ContainerInterface $container)
    {
    }

    public function onBeforeGet(string $className)
    {
        return null;
    }

    public function onAfterGet(string $className, $instance)
    {
    }

    public function onSet($instance, string $name)
    {
    }

    public function onBeforeCreate(string $className)
    {
        return null;
    }

    public function onAfterCreate(string $className, $instance)
    {
    }

    public function onNotFound(string $className)
    {
        return null;
    }
}
