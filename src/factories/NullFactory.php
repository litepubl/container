<?php

namespace LitePubl\Core\Container\Factories;

use LitePubl\Core\Container\NotFound;

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

    public function getInstaller(string $className): InstallerInterface
    {
        return new NullInstaller();
    }
}
