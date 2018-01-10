<?php

namespace LitePubl\Container\Factories;

class NameSpaceFolderFactory extends NameSpaceFactory
{
    protected function getFactoryClass(string $className): string
    {
        $className = ltrim($className, '\\');
        $i = strrpos($className, '\\');
            $ns = substr($className, 0, $i);
        return $ns . '\Factory' . substr($className, $i);
    }
}
