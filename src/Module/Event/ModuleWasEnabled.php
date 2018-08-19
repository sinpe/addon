<?php

namespace Sinpe\Addon\Module\Event;

use Sinpe\Addon\Module\Module;

/**
 * Class ModuleWasEnabled.
 */
class ModuleWasEnabled
{
    /**
     * The module object.
     *
     * @var Module
     */
    protected $module;

    /**
     * Create a new ModuleWasEnabled instance.
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
