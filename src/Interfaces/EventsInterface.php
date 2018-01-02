<?php

namespace LitePubl\Container\Interfaces;

interface EventsInterface
{
    public function setContainer(ContainerInterface $container): void;
    public function onBeforeGet(string $className): ? object;
    public function onAfterGet(string $className, object $instance): void;
    public function onSet(object $instance, ? string $name): void;
    public function onBeforeCreate(string $className): ? object;
    public function onAfterCreate(string $className, object $instance): void;
    public function onNotFound(string $className): ? object;
    public function onDeleted(string $className): void;
    public function onRemoved(object $instance): void;
}
