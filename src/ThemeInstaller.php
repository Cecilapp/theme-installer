<?php

declare(strict_types=1);

namespace Cecil\Installer\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Installs packages of type "cecil-theme" in the "themes/" directory.
 */
class ThemeInstaller extends LibraryInstaller
{
    public const TYPE = 'cecil-theme';
    public const THEMES_DIR = 'themes';
    public const NAME_PATTERN = '/^[a-z0-9][a-z0-9._-]*$/i';

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType === self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return self::THEMES_DIR . '/' . $this->getThemeName($package);
    }

    /**
     * Returns the theme name, from (by priority):
     *  1. "extra.cecil.theme-name"
     *  2. "extra.name" (legacy)
     *  3. the package name, without "theme-" prefix or "-theme" suffix
     *
     * @throws \InvalidArgumentException if the name is not valid
     */
    public function getThemeName(PackageInterface $package): string
    {
        $extra = $package->getExtra();

        if (isset($extra['cecil']['theme-name'])) {
            $name = $extra['cecil']['theme-name'];
        } elseif (isset($extra['name'])) {
            $name = $extra['name'];
        } else {
            $name = substr($package->getPrettyName(), strpos($package->getPrettyName(), '/') + 1);
            $name = preg_replace('/^(cecil-)?theme-|-theme$/', '', $name);
        }

        // no path separator nor leading dot: the theme can't be installed outside of the themes directory
        if (!\is_string($name) || !preg_match(self::NAME_PATTERN, $name)) {
            throw new \InvalidArgumentException(sprintf(
                'Unable to install theme "%s": invalid theme name %s (allowed characters: letters, digits, ".", "_" and "-").',
                $package->getPrettyName(),
                json_encode($name)
            ));
        }

        return $name;
    }
}
