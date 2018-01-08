<?php

namespace LitePubl\Container\DI;

use LitePubl\Container\Composite\Composite;
use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Interfaces\ArrayContainerInterface;

class CompositeArgs extends Composite implements ArrayContainerInterface
{
    public function set(string $className, array $args): void
    {
        if (($this->current !== null) && isset($this->items[$this->current]) && $this->items[$this->current]->has($className)) {
                $this->items[$this->current]->set($className, $args);
        } else {
            foreach ($this->items as $i => $container) {
                if ($container->has($className)) {
                    $this->current = $i;
                    $container->set($className, $args);
                    return;
                }
            }

            throw new NotFound($className);
        }
    }
}
