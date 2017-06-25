<?php

namespace LitePubl\Core\Container\DI;

use Psr\Container\ContainerInterface;

interface DIInterface
{
    public function createInstance(string $className, ContainerInterface $container);
}
