<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface DIInterface
{
    public function createInstance(string $className, ContainerInterface $container);
}
