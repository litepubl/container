<?php

namespace LitePubl\Container\Factories;

use LitePubl\Container\Interfaces\FactoryInterface;
use Psr\Container\ContainerInterface;

abstract class Base implements FactoryInterface
{
    protected $container;
    protected $classMap = [];
    protected $implementations = [];
    protected $installers = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getImplements(string $className): ? string
    {
        return $this->implementations[$className] ?? null;
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
        if (isset($this->classMap[$className])) {
            $result = null;
            $method = $this->classMap[$className];
            if ($method && method_exists($this, $method)) {
                        $result = $this->$method();
            } else {
                $name = substr($className, strrpos($className, '\\') + 1);
                $method = 'create' . $name;
                if (method_exists($this, $method)) {
                        $result = $this->$method();
                } else {
                    $method = 'get' . $name;
                    if (method_exists($this, $method)) {
                        $result = $this->$method();
                    }
                }
            }

            if ($result) {
                        return $result;
            }
        }
        
        throw new NotFound($className);
    }

    public function has($className)
    {
        return isset($this->classMap[ltrim($className, '\\')]);
    }

    public function set(string $className, string $method)
    {
        $this->classMap[$className] = $method;
    }

    public function getInstaller(string $className): InstallerInterface
    {
        $className = ltrim($className, '\\');
        if (isset($this->installers[$className])) {
                $installerClass = $this->installers[$className];
        } elseif (class_exists($className . InstallerInterface::INSTALLER)) {
                $installerClass = $className . InstallerInterface::INSTALLER;
        } else {
            $ns = substr($className, 0, strrpos($className, '\\') + 1);
            if (class_exists($ns .InstallerInterface::INSTALLER)) {
                $installerClass = $ns . InstallerInterface::INSTALLER;
            } else {
                return $this->container->get(InstallerInterface::class);
            }
        }

        if ($this->has($installerClass)) {
                $result = $this->get($installerClass);
        } elseif ($this->container->has(installerClass)) {
                $result = $this->container->get(installerClass);
        } else {
                throw new NotFound(installerClass);
        }

        return $result;
    }
}
