<?php

namespace LitePubl\Container\Installer;

use LitePubl\Container\Interfaces\InstallerInterface;

class NullInstaller implements InstallerInterface
{
    public function install($instance)
    {
    }

    public function uninstall($instance)
    {
    }
}
