<?php

namespace LitePubl\Container\Container;

use LitePubl\Container\Interfaces\EventsInterface;

class NullEvents implements EventsInterface
{

    public function setContainer(ContainerInterface $container): void
    {
    }

    public function onBeforeGet(string $className): ? object
    {
        return null;
    }

    public function onAfterGet(string $className, object $instance): void
    {
    }

    public function onSet(object $instance, ? string $name): void
    {
    }

    public function onBeforeCreate(string $className): ? object
    {
        return null;
    }

    public function onAfterCreate(string $className, object $instance): void
    {
    }

    public function onNotFound(string $className): ? object
    {
        return null;
    }

    public function onDeleted(string $className): void
    {
    }

    public function onRemoved(object $instance): void
    {
    }
}
