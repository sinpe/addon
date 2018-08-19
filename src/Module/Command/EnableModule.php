<?php

namespace Sinpe\Addon\Module\Command;

use Sinpe\Addon\Module\Contract\RepositoryInterface;
use Sinpe\Addon\Module\Event\ModuleWasEnabled;
use Sinpe\Addon\Module\Module;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class EnableModule.
 */
class EnableModule
{
    /**
     * The module to uninstall.
     *
     * @var Module
     */
    protected $module;

    /**
     * Create a new EnableModule instance.
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
        $modules->enabled($this->module);

        $events->fire(new ModuleWasEnabled($this->module));

        return true;
    }
}
