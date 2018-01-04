<?php

namespace LitePubl\Container\Interfaces;

interface CacheReflectionInterface
{
    public function has(string $className): bool;
    public function get(string $className): array;
    public function set(string $className, array $args): void;
}
