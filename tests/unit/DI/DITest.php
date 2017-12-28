<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\DI;
use LitePubl\Container\Interfaces\DIInterface;
use LitePubl\Container\Interfaces\ArgsInterface;
use LitePubl\Container\Interfaces\CacheReflectionInterface;
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

    public function testDI()
    {
                $args = $this->prophesize(ArgsInterface::class);
                $cache = $this->prophesize(CacheReflectionInterface::class);
//codecept_debug(var_export(class_implements($args->reveal()), true));
        $di = new DI($args->reveal());
//, $cache->reveal());
        $this->assertInstanceOf(DI::class, $di);
        $this->assertInstanceOf(DIInterface::class, $di);
        $this->assertInstanceOf(ContainerInterface::class, $di);

                $container = $this->prophesize(ContainerInterface::class);
        $instance = $di->createInstance(Mok::class, $container->reveal());
        $this->assertInstanceOf(Mok::class, $instance);

        $this->assertTrue($di->has(MokConstructor::class));
        $instance = $di->get(MokConstructor::class);
        $this->assertInstanceOf(MokConstructor::class, $instance);
        $this->assertInstanceOf(Mok::class, $instance->getMok());
    }
}
