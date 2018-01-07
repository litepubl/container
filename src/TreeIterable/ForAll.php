<?php

namespace LitePubl\Container\TreeIterable;

use \IteratorAggregate;
use \Traversable;

class ForAll implements IteratorAggregate
{
    protected $container;

    public function __construct(iterable $container)
    {
        $this->container = $container;
    }

    public function getIterator()
    {
        return (function () {
            foreach ($this->container as $name => $instance) {
                    yield $name => $instance;

                if (($instance instanceof Traversable) || is_array($instance)) {
                    yield from (new self($instance));
                }
            }
        })();
    }
}
