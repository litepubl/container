<?php

namespace tests\container\unit\Composite;

use LitePubl\Container\Composite\Composite;
use LitePubl\Container\Exceptions\NotFound;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\unit\Mok;
use \IteratorAggregate;

class CompositeTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testConstruct()
    {
        $container = $this->prophesize(ContainerInterface::class);
                $composite = new Composite($container->reveal());

        $this->assertInstanceOf(Composite::class, $composite);
        $this->assertInstanceOf(ContainerInterface::class, $composite);
        $this->assertInstanceOf(IteratorAggregate::class, $composite);
    }

    public function testHas()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(false)->shouldBeCalled();
                $composite = new Composite($container->reveal());

        $this->assertFalse($composite->has(Mok::class));
        $container->has(Mok::class)->willReturn(true)->shouldBeCalled();
        $this->assertTrue($composite->has(Mok::class));
    }

    public function testGet()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(true)->shouldBeCalled();
        $container->get(Mok::class)->willReturn(new Mok())->shouldBeCalled();
                $composite = new Composite($container->reveal());

        $this->assertInstanceOf(Mok::class, $composite->get(Mok::class));
    }

    public function testNotFound()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(false)->shouldBeCalled();
                $composite = new Composite($container->reveal());

        $this->assertFalse($composite->has(Mok::class));
        $this->expectException(NotFoundExceptionInterface ::class);
                $composite->get(Mok::class);
    }

    public function testIterator()
    {
        $container = $this->prophesize(ContainerInterface::class);
                $composite = new Composite($container->reveal());

        foreach ($composite as $instance) {
            $this->assertInstanceOf(ContainerInterface::class, $instance);
        }
    }

    public function testAdd()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(false)->shouldBeCalled();
                $composite = new Composite($container->reveal());

        $this->assertFalse($composite->has(Mok::class));

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(true)->shouldBeCalled();
                $composite->add($container->reveal());
        $this->assertTrue($composite->has(Mok::class));
    }

    public function testAddFirst()
    {
        $container = $this->prophesize(ContainerInterface::class);
                $composite = new Composite($container->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(true)->shouldBeCalled();
                $composite->addFirst($container->reveal());
        $this->assertTrue($composite->has(Mok::class));
    }

    public function testRemove()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Mok::class)->willReturn(true)->shouldBeCalled();
                $composite = new Composite($container->reveal());
        $this->assertTrue($composite->has(Mok::class));
        $this->assertTrue($composite->remove($container->reveal()));
        $this->assertFalse($composite->has(Mok::class));
        $this->assertFalse($composite->remove($container->reveal()));
    }
}
