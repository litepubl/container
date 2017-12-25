<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function getFactory(): FactoryInterface;
    public function setFactory(FactoryInterface $factory);
    public function getEvents(): EventsInterface;
    public function setEvents(EventsInterface $events);
    public function set($instance, string $name = '');
    public function createInstance(string $className);
    public function delete(string $className): bool;
    public function remove($instance): bool;
}
