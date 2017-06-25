<?php

namespace LitePubl\Core\Container\Factories;

interface InstallableInterface
{
    public function install();
    public function uninstall();
}
