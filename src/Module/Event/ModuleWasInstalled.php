<?php

namespace Sinpe\Addon\Module\Event;

use Sinpe\Addon\Module\Module;

/**
 * Class ModuleWasInstalled.
 */
class ModuleWasInstalled
{
    /**
     * The module object.
     *
     * @var \Sinpe\Addon\Module\Module
     */
    protected $module;

    /**
     * Create a new ModuleWasInstalled instance.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Get the module object.
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }
}
