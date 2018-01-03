<?php

namespace tests\container\unit;

class MokConstructor
{
    private $mok;

    const ARGS = [
    [
      'type' => 'classname',
      'name' => 'mok',
      'value' => Mok::class,
    ],
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
