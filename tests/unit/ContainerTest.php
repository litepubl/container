<?php

namespace LitePubl\Tests\Container;

use LitePubl\Core\Container\Container;
use LitePubl\Core\Container\ContainerInterface;
use LitePubl\Core\Container\EventsInterface;
use LitePubl\Core\Container\Factories\FactoryInterface;
use LitePubl\Core\Container\IterableContainerInterface;

class ContainerTest extends \Codeception\Test\Unit
{
    public function testMe()
    {
                $factory = $this->prophesize(FactoryInterface::class);
                $events = $this->prophesize(EventsInterface::class);
        $container = new Container($factory->reveal(), $events->reveal());
        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertInstanceOf(IterableContainerInterface::class, $container);
    }
}
