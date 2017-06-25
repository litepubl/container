<?php

namespace LitePubl\Core\Container\Factories;

class NullInstaller implements InstallerInterface
{
    public function install($instance)
    {
    }

    public function uninstall($instance)
    {
    }
}
