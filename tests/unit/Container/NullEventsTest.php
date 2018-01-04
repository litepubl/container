<?php

namespace tests\container\unit\Container;

use LitePubl\Container\Container\NullEvents;
use LitePubl\Container\Interfaces\ContainerInterface;
use LitePubl\Container\Interfaces\EventsInterface;

class NullEventsTest extends \Codeception\Test\Unit
{
    public function testNullEvents()
    {
        $events = new NullEvents();
        $this->assertInstanceOf(NullEvents::class, $events);
        $this->assertInstanceOf(EventsInterface::class, $events);

                $container = $this->prophesize(ContainerInterface::class);
        $events->setContainer($container->reveal());

        $this->assertNull($events->onBeforeGet(''));
        $events->onAfterGet('', new \StdClass());
        $events->onSet(new \StdClass(), null);
        $this->assertNull($events->onBeforeCreate(''));
        $events->onAfterCreate('', new \StdClass());
        $this->assertNull($events->onNotFound(''));
        $events->onDeleted('');
        $events->onRemoved(new \StdClass());
    }
}
