<?php

namespace litepubl\core\instances\factories;

use Psr\Container\ContainerInterface;

interface FactoryInterface extends ContainerInterface
{
    public function getImplementation(string $className): string;
}
