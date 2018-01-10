<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface InstallerManagerInterface extends ContainerInterface
{
    public function getInstaller(string $className): InstallerInterface;
}
