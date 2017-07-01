<?php

namespace LitePubl\Tests\Container;

use LitePubl\Core\Container\DI\Cache;
use LitePubl\Core\Container\DI\CacheInterface;
use LitePubl\Core\Container\NotFound;
use Psr\Container\NotFoundExceptionInterface ;
use Psr\Container\ContainerInterface;
use Prophecy\Argument;

class DICacheTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testDICache()
    {
        $cache = new Cache();
        $this->assertInstanceOf(Cache::class, $cache);
        $this->assertInstanceOf(CacheInterface::class, $cache);
        $this->assertInstanceOf(ContainerInterface::class, $cache);

        $this->assertFalse($cache->has(Mok::class));
        $this->tester->expectException(NotFoundExceptionInterface ::class, function () use ($cache) {
                $cache->get(Mok::class);
        });

        $mok = new Mok();
        $cache->set(Mok::class, $mok->data);
        $this->assertTrue($cache->has(Mok::class));
        $this->assertSame($mok->data, $cache->get(Mok::class));
    }
}
