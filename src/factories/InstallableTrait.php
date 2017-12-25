<?php

namespace LitePubl\Container\Factories;

use LitePubl\Container\Interfaces\FactoryInterface;

trait InstallableTrait
{
    abstract public function getFactory(): FactoryInterface;

    public function install()
    {
        $this->getFactory()->getInstaller(get_class($this))->install($this);
    }

    public function uninstall()
    {
        $this->getFactory()->getInstaller(get_class($this))->uninstall($this);
    }
}
