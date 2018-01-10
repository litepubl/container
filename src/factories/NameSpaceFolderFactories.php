<?php

namespace LitePubl\Container\Factories;

class NameSpaceFolderFactories extends NameSpaceFolderFactory
{
    protected function getFactoryClass(string $className): string
    {
        return parent::getFactoryClass($className) . 'Factory';
    }
}
