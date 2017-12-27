<?php

namespace LitePubl\Container\Exceptions;

class UnknownArgTypeException extends \InvalidArgumentException
{
    const FORMAT = 'Unknown "%3$s" argument type for "%2$s" in constructor "%1$s"';
    protected $className;
    protected $name;
    protected $type;

    public function __construct(string $className, string $name, string $type, \Throwable $previous = null)
    {
        $this->className = $className;
        $this->name = $name;
        $this->type = ?$type;

        parent::__construct(sprintf(static::FORMAT, $className, $name, $type), 0, $previous);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
