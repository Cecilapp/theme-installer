<?php

declare(strict_types=1);

namespace Cecil\Installer\Composer\Tests;

use Cecil\Installer\Composer\ThemeInstaller;
use Composer\Composer;
use Composer\Config;
use Composer\Downloader\DownloadManager;
use Composer\IO\NullIO;
use Composer\Package\Package;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ThemeInstallerTest extends TestCase
{
    private ThemeInstaller $installer;

    protected function setUp(): void
    {
        $composer = new Composer();
        $composer->setConfig(new Config(false, sys_get_temp_dir()));
        $composer->setDownloadManager(new DownloadManager(new NullIO()));
        $this->installer = new ThemeInstaller(new NullIO(), $composer);
    }

    public function testSupportsOnlyCecilThemeType(): void
    {
        $this->assertTrue($this->installer->supports('cecil-theme'));
        $this->assertFalse($this->installer->supports('library'));
        $this->assertFalse($this->installer->supports('composer-plugin'));
    }

    public static function validNameProvider(): array
    {
        return [
            'extra.cecil.theme-name' => ['vendor/whatever', ['cecil' => ['theme-name' => 'serviceworker']], 'serviceworker'],
            'extra.cecil over legacy' => ['vendor/whatever', ['cecil' => ['theme-name' => 'new'], 'name' => 'old'], 'new'],
            'legacy extra.name' => ['vendor/whatever', ['name' => 'serviceworker'], 'serviceworker'],
            'package name' => ['vendor/serviceworker', [], 'serviceworker'],
            'package name, theme- prefix' => ['cecil/theme-serviceworker', [], 'serviceworker'],
            'package name, cecil-theme- prefix' => ['vendor/cecil-theme-foo', [], 'foo'],
            'package name, -theme suffix' => ['vendor/foo-theme', [], 'foo'],
            'dots and underscores' => ['vendor/whatever', ['name' => 'my_theme.v2'], 'my_theme.v2'],
        ];
    }

    #[DataProvider('validNameProvider')]
    public function testInstallPath(string $packageName, array $extra, string $expectedName): void
    {
        $package = $this->createPackage($packageName, $extra);

        $this->assertSame($expectedName, $this->installer->getThemeName($package));
        $this->assertSame('themes/' . $expectedName, $this->installer->getInstallPath($package));
    }

    public static function invalidNameProvider(): array
    {
        return [
            'parent traversal' => ['../../src'],
            'nested path' => ['foo/bar'],
            'backslash' => ['foo\\bar'],
            'absolute path' => ['/etc'],
            'dot' => ['.'],
            'double dot' => ['..'],
            'hidden' => ['.git'],
            'empty' => [''],
            'space' => ['my theme'],
            'not a string' => [['foo']],
            'integer' => [42],
        ];
    }

    #[DataProvider('invalidNameProvider')]
    public function testInvalidNameThrows(mixed $name): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->installer->getInstallPath($this->createPackage('vendor/theme', ['name' => $name]));
    }

    private function createPackage(string $name, array $extra): Package
    {
        $package = new Package($name, '1.0.0.0', '1.0.0');
        $package->setType(ThemeInstaller::TYPE);
        $package->setExtra($extra);

        return $package;
    }
}
