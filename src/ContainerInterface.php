<?php

namespace litepubl\core\container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public function set($instance, string $name = '');
    public function createInstance(string $className);
}
