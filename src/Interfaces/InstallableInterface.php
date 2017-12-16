<?php

namespace LitePubl\Core\Container\Interfaces;

interface InstallableInterface
{
    public function install();
    public function uninstall();
}
