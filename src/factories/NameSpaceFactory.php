<?php
namespace litepubl\core\instances\factories;

use Psr\Container\ContainerInterface;

class NameSpaceFactory implements FactoryInterface
{

    public function __construct()
    {
    }

    public function get($className)
    {
        $className = ltrim($className, '\\');
            $ns = substr($className, 0, strrpos($className, '\\'));
        return $ns . '\Factory';
    }

    public function has($className)
    {
        $factoryClass = $this->get($className);
        return ($className != $factoryClass) && class_exists($factoryClass);
    }

    public function getImplementation(string $className): string
    {
        return '';
    }
}
