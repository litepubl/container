<?php

namespace tests\container\unit;

class Mok
{
    public $data = [
    's' => 'v',
    'i' => 4,
    'b' => false,
    'f' => 3.14,
    'a' => [
    'q' => 'w',
    ],
    'items' => [
    ['id' => 1],
    ['id' => 2],
    ]
    ];

    const ARGS = [];
    const REFLECTED = [];

    public function __construct()
    {
    }
}
