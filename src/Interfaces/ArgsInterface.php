<?php

namespace LitePubl\Core\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface ArgsInterface extends ContainerInterface
{
    public function set(string $className, array $args);
}
