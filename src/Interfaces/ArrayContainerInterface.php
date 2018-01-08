<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ArrayContainerInterface extends PsrContainerInterface
{
    public function set(string $className, array $args): void;
}
