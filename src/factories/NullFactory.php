<?php

namespace litepubl\core\instances\factories;

use litepubl\core\instances\NotFound;

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
