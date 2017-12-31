<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function getFactory(): FactoryInterface;
    public function setFactory(FactoryInterface $factory): void;
    public function getEvents(): EventsInterface;
    public function setEvents(EventsInterface $events): void;
    public function set(object $instance, ?string $name): void;
    public function createInstance(string $className): object;
    public function delete(string $className): bool;
    public function remove(object $instance): bool;
}
