<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

interface FactoryInterface extends ContainerInterface
{
    public function set(string $className, string $factoryClass);
}
