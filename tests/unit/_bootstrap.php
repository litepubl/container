<?php
// Here you can initialize variables that will be available to your tests
use Codeception\Util\Autoload;

Autoload::addNamespace('LitePubl\Core\Container', __DIR__ . '/../../src');
Autoload::addNamespace('LitePubl\Tests\Container', __DIR__);
