<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\CompositeArgs;
use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Interfaces\ArrayContainerInterface;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\unit\Mok;
use tests\container\unit\PsrContainerTester;

class CompositeArgsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testConstruct()
    {
        $container = $this->prophesize(ArrayContainerInterface::class);
        $container->has(Mok::class)->willReturn(true)->shouldBeCalled();
        $container->set(Mok::class, [])->shouldBeCalled();
        $container->has('unknown')->willReturn(false)->shouldBeCalled();

                $config = new CompositeArgs($container->reveal());
        $this->assertInstanceOf(CompositeArgs::class, $config);
        $config->set(Mok::class, []);

        $psrTester = new PsrContainerTester();
        $psrTester->test($this->tester, $config);
    }
}
