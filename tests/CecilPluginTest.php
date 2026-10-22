<?php

declare(strict_types=1);

namespace Cecil\Installer\Composer\Tests;

use Cecil\Installer\Composer\CecilPlugin;
use Cecil\Installer\Composer\ThemeInstaller;
use Composer\Composer;
use Composer\Config;
use Composer\Downloader\DownloadManager;
use Composer\Installer\InstallationManager;
use Composer\IO\NullIO;
use Composer\Util\HttpDownloader;
use Composer\Util\Loop;
use PHPUnit\Framework\TestCase;

class CecilPluginTest extends TestCase
{
    public function testActivateRegistersAndDeactivateRemovesInstaller(): void
    {
        $io = new NullIO();
        $config = new Config(false, sys_get_temp_dir());
        $installationManager = new InstallationManager(new Loop(new HttpDownloader($io, $config)), $io);
        $composer = new Composer();
        $composer->setConfig($config);
        $composer->setInstallationManager($installationManager);
        $composer->setDownloadManager(new DownloadManager($io));

        $plugin = new CecilPlugin();

        $plugin->activate($composer, $io);
        $this->assertInstanceOf(ThemeInstaller::class, $installationManager->getInstaller(ThemeInstaller::TYPE));

        $plugin->deactivate($composer, $io);
        $this->expectException(\InvalidArgumentException::class);
        $installationManager->getInstaller(ThemeInstaller::TYPE);
    }
}
