<?php

namespace LitePubl\Tests\Container;

use LitePubl\Core\Container\DI\Args;
use LitePubl\Core\Container\DI\ArgsInterface;
use LitePubl\Core\Container\NotFound;
use Psr\Container\NotFoundExceptionInterface ;
use Psr\Container\ContainerInterface;
use Prophecy\Argument;

class DIArgsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testDIArgs()
    {
        $args = new args();
        $this->assertInstanceOf(Args::class, $args);
        $this->assertInstanceOf(ArgsInterface::class, $args);
        $this->assertInstanceOf(ContainerInterface::class, $args);

        $this->assertFalse($args->has(Mok::class));
        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($args) {
                $args->get(Mok::class);
        });

        $mok = new Mok();
        $args->set(Mok::class, $mok->data);
        $this->assertTrue($args->has(Mok::class));
        $this->assertSame($mok->data, $args->get(Mok::class));
    }
}
