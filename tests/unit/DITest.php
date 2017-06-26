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
        return;
        //$this->assertInstanceOf(IterableContainerInterface::class, $di);
        $this->assertInstanceOf(FactoryInterface::class, $di->getFactory());
        $this->assertInstanceOf(EventsInterface::class, $di->getEvents());

        $this->assertTrue($factory->reveal() === $di->getFactory());
                $factory = $this->prophesize(FactoryInterface::class);
        $this->assertFalse($factory->reveal() === $di->getFactory());
        $di->setFactory($factory->reveal());
        $this->assertTrue($factory->reveal() === $di->getFactory());

        $this->assertTrue($events->reveal() === $di->getEvents());
                $events = $this->prophesize(EventsInterface::class);
        $this->assertFalse($events->reveal() === $di->getEvents());
        $di->setEvents($events->reveal());
        $this->assertTrue($events->reveal() === $di->getEvents());

        $this->assertFalse($di->has(Mok::class));
        $mok = new Mok();
        $di->set($mok);
        $this->assertTrue($di->has(Mok::class));
        $this->assertTrue($mok === $di->get(Mok::class));
        $this->assertTrue($di->delete(Mok::class));
        $this->assertFalse($di->has(Mok::class));
        $this->assertFalse($di->delete(Mok::class));
        $di->set($mok);
        $this->assertTrue($di->has(Mok::class));
        $this->assertTrue($di->remove($mok));
        $this->assertFalse($di->has(Mok::class));
        $this->assertFalse($di->remove($mok));

        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($di) {
                $di->get(Unknown::class);
        });

        $this->tester->expectException(NotFound::class, function () use ($di) {
                $di->get(Unknown::class);
        });

                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn('');
        $factory->has(Argument::type('string'))->willReturn(false);
        $di->setFactory($factory->reveal());

        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($di) {
                $di->get(Mok::class);
        });

        $factory->has(Mok::class)->willReturn(true);
        $factory->get(Mok::class)->willReturn(new Mok());
        $di->setFactory($factory->reveal());

                $this->assertInstanceOf(Mok::class, $di->createInstance(Mok::class));
                $this->assertInstanceOf(Mok::class, $di->get(Mok::class));
                $this->assertTrue($di->has(Mok::class));

        $factory->get(Mok::class)->will(function () use ($di) {
            return $di->get(Mok::class);
        });

        $di->setFactory($factory->reveal());
        $di->delete(Mok::class);
        $this->tester->expectException(CircleException::class, function () use ($di) {
                $di->get(Mok::class);
        });
    }
}
