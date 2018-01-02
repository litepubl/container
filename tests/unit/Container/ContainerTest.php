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
use tests\container\unit\MokScalar;
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

    public function testSet()
    {
        $container = $this->createContainer();
        $this->assertFalse($container->has(Mok::class));
        $mok = new Mok();
        $container->set($mok);
        $this->assertTrue($container->has(Mok::class));
        $this->assertTrue($mok === $container->get(Mok::class));
    }

    public function testSetWithName()
    {
        $container = $this->createContainer();
        $this->assertFalse($container->has(Mok::class));
        $mok = new Mok();
        $name = md5(microtime(true));
        $container->set($mok, $name);
        $this->assertTrue($container->has(Mok::class));
        $this->assertTrue($container->has($name));
        $this->assertTrue($mok === $container->get($name));
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

    public function testUnknownNotFound()
    {
        $container = $this->createContainer();

        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($container) {
                $container->get(Unknown::class);
        });

        $this->tester->expectException(NotFound::class, function () use ($container) {
                $container->get(Unknown::class);
        });
    }

    public function testNotFound()
    {
                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn(null)->shouldBeCalled();
        $factory->has(Argument::type('string'))->willReturn(false)->shouldBeCalled();

                $events = $this->prophesize(EventsInterface::class);
        $events->onBeforeGet(Mok::class)->willReturn(null)->shouldBeCalled();
        $events->onBeforeCreate(Mok::class)->willReturn(null)->shouldBeCalled();
        $events->onNotFound(Mok::class)->willReturn(null)->shouldBeCalled();

        $container = new Container($factory->reveal(), $events->reveal());

        $this->expectException(NotFoundExceptionInterface ::class);
                $container->get(Mok::class);
    }

    public function testGet()
    {
                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn(null)->shouldBeCalled();
        $factory->has(Mok::class)->willReturn(true)->shouldBeCalled();
        $factory->get(Mok::class)->willReturn(new Mok())->shouldBeCalled();

                $events = $this->prophesize(EventsInterface::class);
        $events->onBeforeGet(Mok::class)->willReturn(null)->shouldBeCalled();
        $events->onAfterGet(Mok::class, Argument::type(Mok::class))->shouldBeCalled();
        $events->onBeforeCreate(Mok::class)->willReturn(null)->shouldBeCalled();
        $events->onAfterCreate(Mok::class, Argument::type(Mok::class))->shouldBeCalled();

        $container = new Container($factory->reveal(), $events->reveal());

                $this->assertFalse($container->has(Mok::class));
                $this->assertInstanceOf(Mok::class, $container->get(Mok::class));
                $this->assertTrue($container->has(Mok::class));
    }

    public function testCreateInstance()
    {
                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Argument::type('string'))->willReturn(null)->shouldBeCalled();
        $factory->has(Mok::class)->willReturn(true)->shouldBeCalled();
        $factory->get(Mok::class)->willReturn(new Mok())->shouldBeCalled();

                $events = $this->prophesize(EventsInterface::class);
        $events->onBeforeCreate(Mok::class)->willReturn(null)->shouldBeCalled();
        $events->onAfterCreate(Mok::class, Argument::type(Mok::class))->shouldBeCalled();

        $container = new Container($factory->reveal(), $events->reveal());

                $this->assertFalse($container->has(Mok::class));
                $this->assertInstanceOf(Mok::class, $container->createInstance(Mok::class));
                $this->assertFalse($container->has(Mok::class));
    }

    public function testOnBeforeCreate()
    {
                $factory = $this->prophesize(FactoryInterface::class);

                $events = $this->prophesize(EventsInterface::class);
        $events->onBeforeCreate(Mok::class)->willReturn(new Mok())->shouldBeCalled();
        $events->onAfterCreate(Mok::class, Argument::type(Mok::class))->shouldBeCalled();

        $container = new Container($factory->reveal(), $events->reveal());

                $this->assertFalse($container->has(Mok::class));
                $this->assertInstanceOf(Mok::class, $container->createInstance(Mok::class));
                $this->assertFalse($container->has(Mok::class));
    }

    public function testImplements()
    {
                $factory = $this->prophesize(FactoryInterface::class);
        $factory->getImplementation(Mok::class)->willReturn(MokScalar::class)->shouldBeCalled();
        $factory->getImplementation(MokScalar::class)->willReturn(null)->shouldBeCalled();
        $factory->has(MokScalar::class)->willReturn(true)->shouldBeCalled();
        $factory->get(MokScalar::class)->willReturn(new MokScalar(2, 'hi'))->shouldBeCalled();

                $events = $this->prophesize(EventsInterface::class);
        $events->onBeforeGet(MokScalar::class)->willReturn(null)->shouldBeCalled();
        $events->onAfterGet(MokScalar::class, Argument::type(MokScalar::class))->shouldBeCalled();
        $events->onBeforeCreate(Argument::type('string'))->willReturn(null)->shouldBeCalled();
        $events->onAfterCreate(Argument::type('string'), Argument::type('object'))->shouldBeCalled();

        $container = new Container($factory->reveal(), $events->reveal());

                $this->assertFalse($container->has(Mok::class));
                $this->assertInstanceOf(MokScalar::class, $container->createInstance(Mok::class));
                $this->assertFalse($container->has(Mok::class));
                $this->assertTrue($container->has(MokScalar::class));
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

    public function testIterator()
    {
        $container = $this->createContainer();
        foreach ($container as $name => $instance) {
            $this->assertNotEmpty($instance);
        }
    }
}
