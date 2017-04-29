<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

class Composite implements ContainerInterface
{
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function get($className)
    {
        foreach ($this->items as $container) {
            if ($container->has($className)) {
                return $container->get($className);
            }
        }

        throw new NotFound(sprintf('Class %s not found', $className));
    }

    public function has($className)
    {
        foreach ($this->items as $container) {
            if ($container->has($className)) {
                return true;
            }
        }

        return false;
    }
}