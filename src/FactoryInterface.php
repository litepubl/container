<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

interface FactoryInterface extends ContainerInterface
{
    public function getImplementation(string $className): string;
}
