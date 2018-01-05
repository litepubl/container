<?php

namespace tests\container\unit;

use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\UnitTester;

class PsrContainerTester
{
    public function test(UnitTester $tester, ContainerInterface $container)
    {
        $tester->assertTrue($container->has(Mok::class));
        $tester->assertNotNull($container->get(Mok::class));
        $tester->assertFalse($container->has('unknown'));

        $tester->expectException(NotFoundExceptionInterface ::class, function () use ($container) {
            $container->get('unknown');
        });
    }
}
