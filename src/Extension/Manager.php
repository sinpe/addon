<?php

namespace Sinpe\Addon\Extension;

use Sinpe\Addon\Extension\Command\DisableExtension;
use Sinpe\Addon\Extension\Command\EnableExtension;
use Sinpe\Addon\Extension\Command\InstallExtension;
use Sinpe\Addon\Extension\Command\MigrateExtension;
use Sinpe\Addon\Extension\Command\UninstallExtension;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class ExtensionManager.
 */
class Manager
{
    use DispatchesJobs;

    /**
     * Install a module.
     *
     * @param Extension $module
     * @param bool      $seed
     *
     * @return bool
     */
    public function install(Extension $module, $seed = false)
    {
        return $this->dispatch(new InstallExtension($module, $seed));
    }

    /**
     * Migrate a module.
     *
     * @param Extension $module
     * @param bool      $seed
     *
     * @return bool
     */
    public function migrate(Extension $module, $seed = false)
    {
        return $this->dispatch(new MigrateExtension($module, $seed));
    }

    /**
     * Uninstall a module.
     *
     * @param Extension $module
     *
     * @return bool
     */
    public function uninstall(Extension $module)
    {
        return $this->dispatch(new UninstallExtension($module));
    }

    /**
     * Enable a extension.
     *
     * @param Extension $extension
     * @param bool      $seed
     */
    public function enable(Extension $extension)
    {
        $this->dispatch(new EnableExtension($extension));
    }

    /**
     * Disable a extension.
     *
     * @param Extension $extension
     */
    public function disable(Extension $extension)
    {
        $this->dispatch(new DisableExtension($extension));
    }
}
