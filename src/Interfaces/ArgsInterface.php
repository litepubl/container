<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface ArgsInterface
{
    public function get(string $className, ContainerInterface $container): array;
}
