<?php

namespace Sinpe\Addon\Module\Command;

use Sinpe\Addon\Manager;
use Sinpe\Addon\Module\Event\ModuleWasMigrated;
use Sinpe\Addon\Module\Module;
use Anomaly\Streams\Platform\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class MigrateModule.
 */
class MigrateModule
{
    /**
     * The seed flag.
     *
     * @var bool
     */
    protected $seed;

    /**
     * The module to install.
     *
     * @var Module
     */
    protected $module;

    /**
     * Create a new InstallModule instance.
     *
     * @param Module $module
     * @param bool   $seed
     */
    public function __construct(Module $module, $seed = false)
    {
        $this->seed = $seed;
        $this->module = $module;
    }

    /**
     * Handle the command.
     *
     * @param Kernel     $console
     * @param Manager    $manager
     * @param Dispatcher $dispatcher
     *
     * @return bool
     */
    public function handle(Kernel $console, Manager $manager, Dispatcher $dispatcher)
    {
        $this->module->fire('migrating');

        $options = [
            '--addon' => $this->module->getNamespace(),
            '--force' => true,
        ];

        $console->call('migrate', $options);

        $manager->register();

        if ($this->seed) {
            $console->call('db:seed', $options);
        }

        $this->module->fire('migrated');

        $dispatcher->fire(new ModuleWasMigrated($this->module));

        return true;
    }
}
