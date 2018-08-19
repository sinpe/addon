<?php

namespace Sinpe\Addon\Module\Command;

use Sinpe\Addon\Module\RepositoryInterface;
use Sinpe\Addon\Module\Event\ModuleWasDisabled;
use Sinpe\Addon\Module\Module;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class DisableModule.
 */
class DisableModule
{
    /**
     * The module to uninstall.
     *
     * @var Module
     */
    protected $module;

    /**
     * Create a new DisableModule instance.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Handle the command.
     *
     * @param RepositoryInterface $modules
     * @param Dispatcher          $events
     *
     * @return bool
     */
    public function handle(RepositoryInterface $modules, Dispatcher $events)
    {
        $modules->disable($this->module);

        $events->fire(new ModuleWasDisabled($this->module));

        return true;
    }
}
