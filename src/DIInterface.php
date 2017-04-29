<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

interface DIInterface
{
    public function createInstance(string $className, ContainerInterface $container);
}
