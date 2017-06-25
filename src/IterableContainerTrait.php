<?php

namespace LitePubl\Core\Container;

trait IterableContainerTrait
{

    public function forInstances(string $className = ''): IterableInstances
    {
        $result = new IterableInstances($this);
        if ($className) {
                $result->forClass($className);
        }

        return $result;
    }
}
