<?php

namespace litepubl\core\instances\factories;

class Composite extends ContainerComposite implements FactoryInterface
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
