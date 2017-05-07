<?php

namespace litepubl\core\container\factories;

use litepubl\core\container\NotFound;

class NullFactory implements FactoryInterface
{
    public function get($className)
    {
            throw new NotFound($className);
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
