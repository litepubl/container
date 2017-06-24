<?php

namespace litepubl\core\container;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use litepubl\core\container\factories\FactoryInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function set($instance, string $name = '');
    public function createInstance(string $className);
    public function getFactory(): FactoryInterface;
    public functionsetFactory(FactoryInterface $factory);
    public function getEvents(): EventsInterface;
    public function setEvents(EventsInterface $events);
}
