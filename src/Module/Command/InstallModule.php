<?php

namespace Sinpe\Addon\Module\Command;

use Sinpe\Addon\Manager;
use Sinpe\Addon\Module\RepositoryInterface;
use Sinpe\Addon\Module\Event\ModuleWasInstalled;
use Sinpe\Addon\Module\Module;
use Anomaly\Streams\Platform\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class InstallModule.
 */
class InstallModule
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
     * @param Kernel              $console
     * @param Manager             $manager
     * @param Dispatcher          $dispatcher
     * @param RepositoryInterface $modules
     *
     * @return bool
     */
    public function handle(
        Kernel $console,
        Manager $manager,
        Dispatcher $dispatcher,
        RepositoryInterface $modules
    ) {
        $this->module->fire('installing');

        $options = [
            '--addon' => $this->module->getNamespace(),
            '--force' => true,
        ];

        $console->call('migrate', $options);

        $modules->install($this->module);

        $manager->register();

        if ($this->seed) {
            $console->call('db:seed', $options);
        }

        $this->module->fire('installed');

        $dispatcher->fire(new ModuleWasInstalled($this->module));

        return true;
    }
}
