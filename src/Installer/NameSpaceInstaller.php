<?php

namespace LitePubl\Container\Installer;

use LitePubl\Container\Interfaces\InstallerInterface;
use Psr\Container\ContainerInterface;

class NameSpaceInstaller implements InstallerInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function has($className)
    {
        $installerClass = $this->getInstallerClass($className);
        return ($className != $installerClass) && class_exists($installerClass);
    }

    public function get($className)
    {
        $installerClass = $this->getInstallerClass($className);
        return $this->container->get($installerClass);
    }

    public function getInstaller(string $className): InstallerInterface
    {
        return $this->get($className);
    }

    protected function getInstallerClass($className): string
    {
        $className = ltrim($className, '\\');
            $ns = substr($className, 0, strrpos($className, '\\'));
        $result = $ns . '\Install';
        return $ns . '\Installer';
    }
}
