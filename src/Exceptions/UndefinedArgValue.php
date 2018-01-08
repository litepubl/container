<?php

namespace LitePubl\Container\Exceptions;

use LitePubl\Container\interfaces\DIExceptionInterface;

class UndefinedArgValue extends \UnexpectedValueException implements DIExceptionInterface
{
    const FORMAT = 'Undefined argument "%2$s" in constructor "%1$s"';
    protected $className;
    protected $name;

    public function __construct(string $className, string $name, \Throwable $previous = null)
    {
        $this->className = $className;
        $this->name = $name;

        parent::__construct(sprintf(static::FORMAT, $className, $name), 0, $previous);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
