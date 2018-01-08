<?php

namespace LitePubl\Container\Installer;

trait InstallerToSelfTrait
{
    public function install(object $instance): void
    {
        $instance->install();
    }

    public function uninstall(object $instance): void
    {
        $instance->uninstall();
    }

    public function isInstalled(object $instance): bool
    {
        return $instance->isInstalled();
    }
}
