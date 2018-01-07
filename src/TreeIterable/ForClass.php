<?php

namespace LitePubl\Container\TreeIterable;

use \IteratorAggregate;
use \Traversable;

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
                } elseif (($instance instanceof Traversable) || is_array($instance)) {
                    yield from (new self($instance, $this->className));
                }
            }
        })();
    }

    public function __call($name, $args)
    {
        foreach ($this as $instance) {
            call_user_func_array([$instance, $name], $args);
        }

        return $this;
    }
}
