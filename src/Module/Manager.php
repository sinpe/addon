<?php

namespace Sinpe\Addon\Module;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Sinpe\Addon\Module\Command\DisableModule;
use Sinpe\Addon\Module\Command\EnableModule;
use Sinpe\Addon\Module\Command\InstallModule;
use Sinpe\Addon\Module\Command\MigrateModule;
use Sinpe\Addon\Module\Command\UninstallModule;

/**
 * Class ModuleManager.
 */
class Manager
{
    use DispatchesJobs;

    /**
     * Install a module.
     *
     * @param Module $module
     * @param bool   $seed
     */
    public function install(Module $module, $seed = false)
    {
        $this->dispatch(new InstallModule($module, $seed));
    }

    /**
     * Uninstall a module.
     *
     * @param Module $module
     */
    public function uninstall(Module $module)
    {
        $this->dispatch(new UninstallModule($module));
    }

    /**
     * Enable a module.
     *
     * @param Module $module
     * @param bool   $seed
     */
    public function enable(Module $module)
    {
        $this->dispatch(new EnableModule($module));
    }

    /**
     * Disable a module.
     *
     * @param Module $module
     */
    public function disable(Module $module)
    {
        $this->dispatch(new DisableModule($module));
    }

    /**
     * Migrate a module.
     *
     * @param Module $module
     * @param bool   $seed
     */
    public function migrate(Module $module, $seed = false)
    {
        $this->dispatch(new MigrateModule($module, $seed));
    }
}
