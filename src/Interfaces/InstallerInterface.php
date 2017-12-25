<?php

namespace LitePubl\Container\Interfaces;

interface InstallerInterface
{
    const INSTALLER = 'installer';
    public function install($instance);
    public function uninstall($instance);
}
