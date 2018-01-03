<?php

namespace LitePubl\Container\Interfaces;

interface InstallerInterface
{
    const INSTALLER = 'installer';
    public function install(object $instance);
    public function uninstall(object $instance);
}
