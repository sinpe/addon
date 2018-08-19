<?php

namespace Sinpe\Addon\Module\Event;

use Sinpe\Addon\Module\Module;

/**
 * Class ModuleWasRegistered.
 */
class ModuleWasRegistered
{
    /**
     * The module object.
     *
     * @var Module
     */
    protected $module;

    /**
     * Create a new ModuleWasRegistered instance.
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
