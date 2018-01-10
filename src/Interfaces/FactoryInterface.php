<?php

namespace LitePubl\Container\Interfaces;

use Psr\Container\ContainerInterface;

interface FactoryInterface extends ContainerInterface
{
    public function getImplements(string $className): ?string;
}
