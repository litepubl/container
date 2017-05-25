<?php

namespace litepubl\core\container\factories;

interface InstallerInterface
{
    const INSTALLER = 'installer';
    public function install($instance);
    public function uninstall($instance);
}
