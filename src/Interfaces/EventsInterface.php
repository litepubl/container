<?php

namespace LitePubl\Container\Interfaces;

interface EventsInterface
{
    public function setContainer(ContainerInterface $container);
    public function onBeforeGet(string $className);
    public function onAfterGet(string $className, $instance);
    public function onSet($instance, string $name);
    public function onBeforeCreate(string $className);
    public function onAfterCreate(string $className, $instance);
    public function onNotFound(string $className);
    public function onDeleted(string $className);
    public function onRemoved($instance);
}
