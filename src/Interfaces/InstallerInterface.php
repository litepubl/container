<?php

namespace LitePubl\Core\Container\Interfaces;

interface InstallerInterface
{
    const INSTALLER = 'installer';
    public function install($instance);
    public function uninstall($instance);
}
