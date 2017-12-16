<?php

namespace LitePubl\Core\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface FactoryInterface extends ContainerInterface
{
    public function getImplementation(string $className): string;
    public function getInstaller(string $className): InstallerInterface;
}
