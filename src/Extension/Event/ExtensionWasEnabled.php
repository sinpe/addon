<?php

namespace Sinpe\Addon\Extension\Event;

use Sinpe\Addon\Extension\Extension;

/**
 * Class ExtensionWasEnabled.
 */
class ExtensionWasEnabled
{
    /**
     * The module object.
     *
     * @var Extension
     */
    protected $module;

    /**
     * Create a new ExtensionWasEnabled instance.
     *
     * @param Extension $module
     */
    public function __construct(Extension $module)
    {
        $this->module = $module;
    }

    /**
     * Get the module object.
     *
     * @return Extension
     */
    public function getExtension()
    {
        return $this->module;
    }
}
