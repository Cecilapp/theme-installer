<?php

declare(strict_types=1);

namespace Cecil\Installer\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Registers the Cecil theme installer.
 */
class CecilPlugin implements PluginInterface
{
    private ?ThemeInstaller $installer = null;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->installer = new ThemeInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($this->installer);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        if ($this->installer === null) {
            return;
        }
        $composer->getInstallationManager()->removeInstaller($this->installer);
        $this->installer = null;
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
