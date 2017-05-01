<?php

namespace litepubl\core\instances;

use Psr\Container\ContainerInterface;

class Composite implements ContainerInterface
{
    protected $items;

    public function __construct(ContainerInterface ...$items)
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

    public function add(ContainerInterface $item)
    {
        $this->items[] = $item;
    }

    public function remove(ContainerInterface $container): bool
    {
        foreach ($this->items as $i => $item) {
            if ($item == $container) {
                unset($this->items[$i]);
                return true;
            }
        }

        return false;
    }
}
