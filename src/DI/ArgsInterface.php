<?php

namespace litepubl\core\container\DI;

use Psr\Container\ContainerInterface;

interface ArgsInterface
{
    public function get(string $className): array;
    public function set(string $className, array $args);
}
