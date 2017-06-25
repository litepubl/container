<?php

namespace LitePubl\Core\Container\Factories;

use Psr\Container\ContainerInterface;

interface FactoryInterface extends ContainerInterface
{
    public function getImplementation(string $className): string;
    public function getInstaller(string $className): InstallerInterface;
}
