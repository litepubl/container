<?php

namespace LitePubl\Container\Interfaces;

interface SelfInstallerInterface
{
    public function install(): void;
    public function uninstall(): void;
    public function isInstalled(): bool;
}
