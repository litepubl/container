<?php

namespace LitePubl\Container\Interfaces;

interface InstallerInterface
{
    const INSTALLER = 'installer';
    public function install(object $instance): void;
    public function uninstall(object $instance): void;
    public function isInstalled(object $instance): bool;
}
