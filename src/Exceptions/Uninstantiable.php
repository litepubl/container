<?php

namespace LitePubl\Container\Exceptions;

use LitePubl\Container\interface\ArgsExceptionInterface;

class Uninstantiable extends \UnexpectedValueException implements ArgsExceptionInterface
{
    const FORMAT = 'Class"%s" is not instantiable';
    protected $className;

    public function __construct(string $className, \Throwable $previous = null)
    {
        $this->className = $className;

        parent::__construct(sprintf(static::FORMAT, $className), 0, $previous);
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
