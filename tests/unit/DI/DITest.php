<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\DI;
use LitePubl\Container\Interfaces\DIInterface;
use LitePubl\Container\Interfaces\ArgsInterface;
use Psr\Container\ContainerInterface;
use Prophecy\Argument;
use tests\container\unit\Mok;
use tests\container\unit\MokConstructor;

class DITest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testConstructor()
    {
                $args = $this->prophesize(ArgsInterface::class);
        $di = new DI($args->reveal());

        $this->assertInstanceOf(DI::class, $di);
        $this->assertInstanceOf(DIInterface::class, $di);
        $this->assertInstanceOf(ContainerInterface::class, $di);
    }

    public function testMok()
    {
                $args = $this->prophesize(ArgsInterface::class);
        $args->get(Mok::class, Argument::type(ContainerInterface::class))->willReturn([])->shouldBeCalled();
        $di = new DI($args->reveal());
        $this->assertTrue($di->has(Mok::class));

                $container = $this->prophesize(ContainerInterface::class);
        $instance = $di->createInstance(Mok::class, $container->reveal());
        $this->assertInstanceOf(Mok::class, $instance);
    }

    public function testMokConstructor()
    {
                $args = $this->prophesize(ArgsInterface::class);
        $args->get(MokConstructor::class, Argument::type(ContainerInterface::class))->willReturn([new Mok()])->shouldBeCalled();

        $di = new DI($args->reveal());
        $this->assertTrue($di->has(MokConstructor::class));

        $instance = $di->get(MokConstructor::class);
        $this->assertInstanceOf(MokConstructor::class, $instance);
        $this->assertInstanceOf(Mok::class, $instance->getMok());
    }
}
