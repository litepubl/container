<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

class NullFactory implements ContainerInterface
{
    public function get($className)
    {
            throw new NotFound(sprintf('Class "%s" not found', $className));
    }

    public function has($className)
    {
        return false;
    }

    public function getImplementation(string $className): string
    {
        return '';
    }
}
