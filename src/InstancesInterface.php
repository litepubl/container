<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

interface InstancesInterface extends ContainerInterface
{
    public function set($instance, string $name = '');
    public function createInstance(string $className);
}
