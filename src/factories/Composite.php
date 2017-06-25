<?php

namespace LitePubl\Core\Container\Factories;

use LitePubl\Core\Container\patterns\Composite as PatternComposite;

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

    public function getInstaller(string $className): InstallerInterface
    {
        if (($this->current !== null) && isset($this->items[$this->current]) && $this->items[$this->current]->has($className)) {
                return $this->items[$this->current]->getInstaller($className);
        }

        foreach ($this->items as $i => $factory) {
            if ($factory->has($className)) {
                $this->current = $i;
                return $factory->getInstaller($className);
            }
        }
    }
}
