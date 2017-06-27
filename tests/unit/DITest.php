<?php

namespace LitePubl\Tests\Container;

use LitePubl\Core\Container\DI\DI;
use LitePubl\Core\Container\DI\DIInterface;
use LitePubl\Core\Container\DI\ArgsInterface;
use LitePubl\Core\Container\DI\CacheInterface;
use Psr\Container\ContainerInterface;
use Prophecy\Argument;

class DITest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testDI()
    {
                $args = $this->prophesize(ArgsInterface::class);
                $cache = $this->prophesize(CacheInterface::class);
        $di = new DI($args->reveal(), $cache->reveal());
        $this->assertInstanceOf(DI::class, $di);
        $this->assertInstanceOf(DIInterface::class, $di);
        $this->assertInstanceOf(ContainerInterface::class, $di);

                $container = $this->prophesize(ContainerInterface::class);
        $instance = $di->createInstance(Mok::class, $container->reveal());
        $this->assertInstanceOf(Mok::class, $instance);

        $this->assertTrue($di->has(Mokconstructor::class));
        $instance = $di->get(MokConstructor::class);
        $this->assertInstanceOf(MokConstructor::class, $instance);
        $this->assertInstanceOf(Mok::class, $instance->getMok());
    }
}
