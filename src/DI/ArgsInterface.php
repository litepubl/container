<?php

namespace LitePubl\Core\Container\DI;

use Psr\Container\ContainerInterface;

interface ArgsInterface extends ContainerInterface
{
    public function set(string $className, array $args);
}
