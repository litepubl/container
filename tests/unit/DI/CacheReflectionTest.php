<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\CacheReflection;
use LitePubl\Container\Exceptions\NotFound;
use LitePubl\Container\Interfaces\ArrayContainerInterface;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\unit\Mok;
use tests\container\unit\MokConstructor;
use tests\container\unit\MokScalar;

class CacheReflectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testConstruct()
    {
                $cache = new CacheReflection();
        $this->assertInstanceOf(CacheReflection::class, $cache);
        $this->assertInstanceOf(ArrayContainerInterface::class, $cache);
    }

    public function testNotFound()
    {
                $cache = new CacheReflection();
        $this->assertFalse($cache->has(Mok::class));
        $this->expectException(NotFoundExceptionInterface ::class);
                $cache->get(Mok::class);
    }

    public function testGet()
    {
                $cache = new CacheReflection();
        $mok = new Mok();
        $cache->set(Mok::class, $mok->data);
        $this->assertTrue($cache->has(Mok::class));
        $this->assertSame($mok->data, $cache->get(Mok::class));
    }
}
