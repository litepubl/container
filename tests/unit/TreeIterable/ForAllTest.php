<?php

namespace tests\container\unit\TreeIterable;

use LitePubl\Container\TreeIterable\ForAll;
use \IteratorAggregate;
use \Traversable;

class ForAllTest extends \Codeception\Test\Unit
{
    public function testConstruct()
    {
                $forAll = new ForAll([]);

        $this->assertInstanceOf(ForAll::class, $forAll);
        $this->assertInstanceOf(IteratorAggregate::class, $forAll);
    }

    public function testIterator()
    {
        $a = ['one', 'two'];
                $forAll = new ForAll($a);

        foreach ($forAll as $i => $item) {
            $this->assertEquals($a[$i], $item);
        }
    }

    public function testTreeIterator()
    {
                $forAll = new ForAll([
        'v',
        'v',
        new \ArrayIterator([
        'v',
        'v',
                 new ForAll(['v', 'v']),
        ])
                ]);

        $count = 0;
        foreach ($forAll as $i => $item) {
            if (!(($item instanceof Traversable) || is_array($item))) {
                        $this->assertEquals('v', $item);
                $count++;
            }
        }

        $this->assertEquals(6, $count);
    }
}
