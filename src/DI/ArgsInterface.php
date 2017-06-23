<?php

namespace litepubl\core\container\DI;

use Psr\Container\ContainerInterface;

interface ArgsInterface extends ContainerInterface
{
    public function set(string $className, array $args);
}
