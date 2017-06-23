<?php

namespace litepubl\core\container\DI;

use litepubl\core\container\patterns\Composite;
use litepubl\core\container\NotFound;

class CompositeArgs extends Composite implements ArgsInterface
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
