<?php

namespace tests\container\unit;

class MokConstructor
{
    private $mok;

    const ARGS = [
    ];

    public function __construct(Mok $mok)
    {
        $this->mok = $mok;
    }

    public function getMok()
    {
        return $this->mok;
    }
}
