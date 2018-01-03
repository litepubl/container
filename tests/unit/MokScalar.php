<?php

namespace tests\container\unit;

class MokScalar
{
    const ARGS = [
    [
      'type' => 'required',
      'name' => 'i',
    ],
    [
      'type' => 'value',
      'name' => 's',
      'value' => null,
    ],
    [
      'type' => 'value',
      'name' => 'b',
      'value' => true,
    ]
    ];

    public function __construct(int $i, ? string $s, bool $b = true)
    {
    }
}
