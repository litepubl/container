<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\Args;
use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Interfaces\ArgsInterface;
use LitePubl\Container\Interfaces\CacheReflectionInterface;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\unit\Mok;
use tests\container\unit\MokConstructor;
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
        $config->has(Argument::type('string'))->willReturn(false);

                $cache = $this->prophesize(CacheReflectionInterface::class);
        $cache->has(Argument::type('string'))->willReturn(false)->shouldBeCalled();
        $cache->set(Argument::type('string'), Argument::type('array'))->shouldBeCalled();

        $args = new Args($config->reveal(), $cache->reveal());
                $container = $this->prophesize(ContainerInterface::class)->reveal();

        foreach ([Mok::class, MokConstructor::class, MokScalar::class] as $className) {
                $data = $args->get($className, $container);
            codecept_debug(var_export($data, true));
                $this->assertSame($className::ARGS, $data);
        }
    }
}
