<?php

namespace Narno\PHPoole\Composer\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class Installer extends LibraryInstaller
{
    const TYPE_PLUGIN = 'phpoole-plugin';
    const TYPE_THEME  = 'phpoole-theme';
    
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        switch($package->getType())
        {
            case self::TYPE_PLUGIN:
                $dir = 'plugins';
            break;
            
            case self::TYPE_THEME:
                $dir = 'themes';
            break;
        }
        
        $name  = $this->getExtraName($package);
    
        return $dir . '/' . $name;
    }
    
    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return in_array($packageType, [
            self::TYPE_PLUGIN,
            self::TYPE_THEME
        ], true);
    }
    
    /**
     * Get the theme or plugin name from the package extra info
     *
     * @param PackageInterface $package
     * @throws \InvalidArgumentException
     *
     * @return string
    */
    protected function getExtraName(PackageInterface $package)
    {
        $extraData = $package->getExtra();
        
        if (!array_key_exists('name', $extraData)) {
            throw new \InvalidArgumentException(
                'Unable to install theme or plugin, it must include the name in the extra field of composer.json.'
            );
        }
        
        return $extraData['name'];
    }
}
