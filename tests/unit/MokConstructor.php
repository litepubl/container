<?php

namespace LitePubl\Tests\Container;

class MokConstructor
{
    private $mok;

    public function __construct(Mok $mok)
    {
        $this->mok = $mok;
    }

    public function getMok()
    {
        return $this->mok;
    }
}
