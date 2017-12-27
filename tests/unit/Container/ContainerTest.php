<?php

namespace tests\container\unit\Container;

use LitePubl\Container\Container\Container;
use LitePubl\Container\Interfaces\ContainerInterface;
use LitePubl\Container\Interfaces\EventsInterface;
use LitePubl\Container\Interfaces\FactoryInterface;
use LitePubl\Container\Exceptions\Exception;
use LitePubl\Container\Exceptions\CircleException;
use LitePubl\Container\Exceptions\NotFound;
use Psr\Container\NotFoundExceptionInterface ;
use Prophecy\Argument;
use tests\container\unit\Mok;
use \IteratorAggregate;
use \ArrayIterator;

class ContainerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testMe()
    {
                $factory = $this->prophesize(FactoryInterface::class);
                $events = $this->prophesize(EventsInterface::class);
        $container = new Container($factory->reveal(), $events->reveal());
        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertInstanceOf(IteratorAggregate::class, $container);
        $this->assertInstanceOf(FactoryInterface::class, $container->getFactory());
        $this->assertInstanceOf(EventsInterface::class, $container->getEvents());

        $this->assertTrue($factory->reveal() === $container->getFactory());
                $factory = $this->prophesize(FactoryInterface::class);
        $this->assertFalse($factory->reveal() === $container->getFactory());
        $container->setFactory($factory->reveal());
        $this->assertTrue($factory->reveal() === $container->getFactory());

        $this->assertTrue($events->reveal() === $container->getEvents());
                $events = $this->prophesize(EventsInterface::class);
        $this->assertFalse($events->reveal() === $container->getEvents());
        $container->setEvents($events->reveal());
        $this->assertTrue($events->reveal() === $container->getEvents());

        $this->assertFalse($container->has(Mok::class));
        $mok = new Mok();
        $container->set($mok);
        $this->assertTrue($container->has(Mok::class));
        $this->assertTrue($mok === $container->get(Mok::class));
        $this->assertTrue($container->delete(Mok::class));
        $this->assertFalse($container->has(Mok::class));
        $this->assertFalse($container->delete(Mok::class));
        $container->set($mok);
        $this->assertTrue($container->has(Mok::class));
        $this->assertTrue($container->remove($mok));
        $this->assertFalse($container->has(Mok::class));
        $this->assertFalse($container->remove($mok));

        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($container) {
                $container->get(Unknown::class);
        });

        $this->tester->expectException(NotFound::class, function () use ($container) {
                $container->get(Unknown::class);
        });

                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn('');
        $factory->has(Argument::type('string'))->willReturn(false);
        $container->setFactory($factory->reveal());

        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($container) {
                $container->get(Mok::class);
        });

        $factory->has(Mok::class)->willReturn(true);
        $factory->get(Mok::class)->willReturn(new Mok());
        $container->setFactory($factory->reveal());

                $this->assertInstanceOf(Mok::class, $container->createInstance(Mok::class));
                $this->assertInstanceOf(Mok::class, $container->get(Mok::class));
                $this->assertTrue($container->has(Mok::class));

        $factory->get(Mok::class)->will(function () use ($container) {
            return $container->get(Mok::class);
        });

        $container->setFactory($factory->reveal());
        $container->delete(Mok::class);
        $this->tester->expectException(CircleException::class, function () use ($container) {
                $container->get(Mok::class);
        });
    }
}
