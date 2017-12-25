<?php

namespace LitePubl\Container;

use Psr\Container\NotFoundExceptionInterface ;

class NotFound extends \RuntimeException implements NotFoundExceptionInterface
{
    protected $className;

    public function construct(string $className, int $code = 0, \Throwable $previous = null)
    {
        $this->className = $className;
        parent::__construct(sprintf('Class %s not found', $className), $code, $previous);
    }
}
