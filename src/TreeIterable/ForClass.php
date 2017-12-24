<?php

namespace LitePubl\Core\Container\TreeIterable;

use \IteratorAggregate;
use \iterable;

class ForClass implements IteratorAggregate
{
    protected $container;
    protected $className;

    public function __construct(iterable $container, string $className)
    {
        $this->container = $container;
        $this->className = $className;
    }

    public function getIterator()
    {
        return (function () {
            foreach ($this->container as $name => $instance) {
                if ($instance instanceof $this->className) {
                    yield $name => $instance;
                } elseif ($instance instanceof iterable) {
                    $self = new self($instance, $this->className);
                    foreach ($self as $name2 => $instance2) {
                        yield $name2 => $instance2;
                    }
                }
            }
        })();
    }
}
