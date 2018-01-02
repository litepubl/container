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

    protected function createContainer()
    {
                $factory = $this->prophesize(FactoryInterface::class);
                $events = $this->prophesize(EventsInterface::class);
        return new Container($factory->reveal(), $events->reveal());
    }

    public function testConstruct()
    {
        $container = $this->createContainer();
        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertInstanceOf(IteratorAggregate::class, $container);
        $this->assertInstanceOf(FactoryInterface::class, $container->getFactory());
        $this->assertInstanceOf(EventsInterface::class, $container->getEvents());
    }

    public function testFactory()
    {
        $container = $this->createContainer();
                $factory = $this->prophesize(FactoryInterface::class);
        $this->assertFalse($factory->reveal() === $container->getFactory());
        $container->setFactory($factory->reveal());
        $this->assertTrue($factory->reveal() === $container->getFactory());
    }

    public function testEvents()
    {
        $container = $this->createContainer();
                $events = $this->prophesize(EventsInterface::class);
        $this->assertFalse($events->reveal() === $container->getEvents());
        $container->setEvents($events->reveal());
        $this->assertTrue($events->reveal() === $container->getEvents());
    }

    public function testMok()
    {
        $container = $this->createContainer();
        $this->assertFalse($container->has(Mok::class));
        $mok = new Mok();
        $container->set($mok);
        $this->assertTrue($container->has(Mok::class));
        $this->assertTrue($mok === $container->get(Mok::class));
    }

    public function testDelete()
    {
        $container = $this->createContainer();
        $mok = new Mok();
        $container->set($mok);

        $this->assertTrue($container->delete(Mok::class));
        $this->assertFalse($container->has(Mok::class));
        $this->assertFalse($container->delete(Mok::class));
    }

    public function testRemove()
    {
        $container = $this->createContainer();
        $mok = new Mok();
        $container->set($mok);
        $this->assertTrue($container->has(Mok::class));
        $this->assertTrue($container->remove($mok));
        $this->assertFalse($container->has(Mok::class));
        $this->assertFalse($container->remove($mok));
    }

    public function testNotFound()
    {
        $container = $this->createContainer();

        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($container) {
                $container->get(Unknown::class);
        });

        $this->tester->expectException(NotFound::class, function () use ($container) {
                $container->get(Unknown::class);
        });
    }

    public function testFactorryMethods()
    {
        $container = $this->createContainer();
                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn(null);
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
    }

    public function testCircleException()
    {
        $container = $this->createContainer();
                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn(null);
        $factory->has(Mok::class)->willReturn(true);
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
