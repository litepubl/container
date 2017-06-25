<?php

namespace LitePubl\Core\Container\Factories;

interface InstallerInterface
{
    const INSTALLER = 'installer';
    public function install($instance);
    public function uninstall($instance);
}
