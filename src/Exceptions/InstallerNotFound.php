<?php

namespace LitePubl\Container\Exceptions;

class InstallerNotFound extends \RuntimeException
{
    protected $className;

    public function construct(string $className, int $code = 0)
    {
        $this->className = $className;
        parent::__construct(sprintf('Installer for class %s not found', $className), $code);
    }
}
