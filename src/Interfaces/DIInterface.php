<?php

namespace LitePubl\Core\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface DIInterface
{
    public function createInstance(string $className, ContainerInterface $container);
}
