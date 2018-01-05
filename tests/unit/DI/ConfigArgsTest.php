<?php

namespace tests\container\unit\DI;

use LitePubl\Container\DI\ConfigArgs;
use LitePubl\Container\Exceptions\NotFound;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface ;
use tests\container\unit\Mok;
use tests\container\unit\PsrContainerTester;

class ConfigArgsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testConstruct()
    {
                $config = new ConfigArgs();
        $this->assertInstanceOf(ConfigArgs::class, $config);
        $config->set(Mok::class, []);

        $psrTester = new PsrContainerTester();
        $psrTester->test($this->tester, $config);
    }
}
