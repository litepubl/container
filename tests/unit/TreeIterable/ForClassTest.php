<?php

namespace tests\container\unit\TreeIterable;

use LitePubl\Container\TreeIterable\ForClass;
use tests\container\unit\Mok;
use tests\container\unit\MokConstructor;
use \IteratorAggregate;
use \Traversable;

class ForClassTest extends \Codeception\Test\Unit
{
    public function testConstruct()
    {
                $forClass = new ForClass([], Mok::class);

        $this->assertInstanceOf(ForClass::class, $forClass);
        $this->assertInstanceOf(IteratorAggregate::class, $forClass);
    }

    public function testIterator()
    {
        $a = [
        new MokConstructor(new Mok()),
        new Mok(),
        new MokConstructor(new Mok()),
        ];

                $forClass = new ForClass($a, Mok::class);

        foreach ($forClass as $i => $instance) {
            $this->AssertInstanceOf(Mok::class, $instance);
        }
    }

    public function testTreeIterator()
    {
                $forClass = new ForClass([
        new Mok(),
        new MokConstructor(new Mok()),
        new \ArrayIterator([
        new Mok(),
        new MokConstructor(new Mok()),
                 new ForClass([
        new Mok()
                 ], MokConstructor::class),
        ])
                ], Mok::class);

        $count = 0;
        foreach ($forClass as $i => $instance) {
                        $this->assertInstanceOf(Mok::class, $instance);
                $count++;
        }

        $this->assertEquals(2, $count);
    }
}
