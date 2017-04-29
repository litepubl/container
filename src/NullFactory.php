<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

class NullFactory implements FactoryInterface
{
    public function get($className)
    {
            throw new NotFound(sprintf('Class "%s" not found', $className));
    }

    public function has($className)
    {
        return false;
    }

    public function set(string $className, string $factoryClass)
{
}
}
