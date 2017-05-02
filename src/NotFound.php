<?php

namespace litepubl\core\instances;

use Psr\Container\NotFoundExceptionInterface ;

class NotFound extends \RuntimeException implements NotFoundExceptionInterface
{
    public function construct(string $className, int $code = 0)
    {
        parent::__construct(sprintf('Class %s not found', $className), $code);
    }
}
