<?php

namespace LitePubl\Container\DI;

use LitePubl\Container\Composite\Composite;
use LitePubl\Container\Exceptions\NotFound;

class CompositeArgs extends Composite
{
    public function set(string $className, array $args)
    {
        if (($this->current !== null) && isset($this->items[$this->current]) && $this->items[$this->current]->has($className)) {
                $this->items[$this->current]->set($className, $args);
        } else {
            foreach ($this->items as $i => $container) {
                if ($container->has($className)) {
                    $this->current = $i;
                    $container->set($className, $args);
                    break;
                }
            }
        }

        throw new NotFound($className);
    }
}
