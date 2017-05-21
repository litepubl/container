<?php

namespace litepubl\core\container;

interface EventsInterface
{
    public function setContainer(ContainerInterface $container);
    public function onBeforeGet(string $className);
    public function onAfterGet(string $className, $instance);
    public function onSet($instance, string $name);
    public function onBeforeCreate(string $className);
    public function onAfterCreate(string $className, $instance);
    public function onNotFound(string $className);
}
