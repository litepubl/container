<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\Args;
use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Interfaces\ArgsInterface;
use LitePubl\Container\Interfaces\CacheReflectionInterface;
use LitePubl\Container\Exceptions\UndefinedArgValue;
use LitePubl\Container\Exceptions\Uninstantiable;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\unit\Mok;
use tests\container\unit\MokConstructor;
use tests\container\unit\MokDisabled;
use tests\container\unit\MokScalar;

class ArgsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testConstruct()
    {
                $config = $this->prophesize(ContainerInterface::class);
                $cache = $this->prophesize(CacheReflectionInterface::class);
        $args = new Args($config->reveal(), $cache->reveal());

        $this->assertInstanceOf(Args::class, $args);
        $this->assertInstanceOf(ArgsInterface::class, $args);
    }

    public function testGet()
    {
                $config = $this->prophesize(ContainerInterface::class);

                $cache = $this->prophesize(CacheReflectionInterface::class);
        $cache->has(Argument::type('string'))->willReturn(false)->shouldBeCalled();
        $cache->set(Argument::type('string'), Argument::type('array'))->shouldBeCalled();

        $args = new Args($config->reveal(), $cache->reveal());
                $container = $this->prophesize(ContainerInterface::class);

                $data = $args->get(Mok::class, $container->reveal());
        $this->assertEquals(Mok::ARGS, $data);

        $config->has(Argument::type('string'))->willReturn(true)->shouldBeCalled();
        $config->get(MokConstructor::class)->willReturn(MokConstructor::ARGS)->shouldBeCalled();
        $container->get(Mok::class)->willReturn(new Mok())->shouldBeCalled();

                $data = $args->get(MokConstructor::class, $container->reveal());
        $this->assertEquals([new Mok()], $data);

        $config->get(MokScalar::class)->willReturn(MokScalar::CONFIG)->shouldBeCalled();

                $data = $args->get(MokScalar::class, $container->reveal());
        $this->assertEquals(MokScalar::ARGS, $data);
    }

    public function testUndefinedArgValue()
    {
                $config = $this->prophesize(ContainerInterface::class);
        $config->has(MokScalar::class)->willReturn(true)->shouldBeCalled();
        $config->get(MokScalar::class)->willReturn(['s' => 'some'])->shouldBeCalled();

                $cache = $this->prophesize(CacheReflectionInterface::class);
        $cache->has(Argument::type('string'))->willReturn(false)->shouldBeCalled();
        $cache->set(Argument::type('string'), Argument::type('array'))->shouldBeCalled();

        $args = new Args($config->reveal(), $cache->reveal());
                $container = $this->prophesize(ContainerInterface::class);

        $this->expectException(UndefinedArgValue::class);
        $args->get(MokScalar::class, $container->reveal());
    }

    public function testUninstantiable()
    {
                $config = $this->prophesize(ContainerInterface::class);
                $cache = $this->prophesize(CacheReflectionInterface::class);
        $cache->has(Argument::type('string'))->willReturn(false)->shouldBeCalled();

        $args = new Args($config->reveal(), $cache->reveal());
                $container = $this->prophesize(ContainerInterface::class);

        $this->expectException(Uninstantiable::class);
        $args->get(MokDisabled::class, $container->reveal());
    }
    public function testGetReflectedParams()
    {
                $config = $this->prophesize(ContainerInterface::class);
                $cache = $this->prophesize(CacheReflectionInterface::class);
        $args = new Args($config->reveal(), $cache->reveal());

        foreach ([Mok::class, MokConstructor::class, MokScalar::class] as $className) {
                $data = $args->getReflectedParams($className);
                 $this->assertSame($className::REFLECTED, $data);
        }
    }
}
