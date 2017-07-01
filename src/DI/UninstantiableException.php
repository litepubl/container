<?php

namespace LitePubl\Core\Container\DI;

class UninstantiableException extends \UnexpectedValueException
{
    const MESSAGE = 'Class"%s" is not instantiable';
    protected $className;

    public function __construct(string $className)
    {
        $this->className = $className;

        parent::__construct(sprintf(static::MESSAGE, $className));
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
