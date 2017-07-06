<?php

namespace LitePubl\Core\Container\DI;

class UndefinedArgValueException extends \InvalidArgumentException
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
