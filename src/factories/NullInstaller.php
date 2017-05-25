<?php

namespace litepubl\core\container\factories;

class NullInstaller implements InstallerInterface
{
    public function install($instance)
    {
    }

    public function uninstall($instance)
    {
    }
}
