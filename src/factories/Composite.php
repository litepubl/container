<?php

namespace litepubl\core\container\factories;

use litepubl\core\container\patterns\Composite as PatternComposite;

class Composite extends PatternComposite implements FactoryInterface
{

    public function __construct(FactoryInterface ...$items)
    {
        $this->items = $items;
    }

    public function getImplementation(string $className): string
    {
        if ($this->current && isset($this->items[$this->current]) && ($result = $this->items[$this->current]->getImplementation($className))) {
                return $result;
        }

        foreach ($this->items as $i => $container) {
            if ($result = $container->getImplementation($className)) {
                $this->current = $i;
                return $result;
            }
        }

        return '';
    }
}
