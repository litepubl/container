<?php

namespace LitePubl\Core\Container\DI;

use Psr\Container\ContainerInterface;

interface CacheInterface extends ContainerInterface
{
    public function set(string $className, array $args);
}
