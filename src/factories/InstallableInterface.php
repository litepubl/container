<?php

namespace litepubl\core\container\factories;

interface InstallableInterface
{
    public function install();
    public function uninstall();
}
