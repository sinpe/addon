<?php

namespace Sinpe\Addon\Extension\Event;

use Sinpe\Addon\Extension\Extension;

/**
 * Class ExtensionWasDisabled.
 */
class ExtensionWasDisabled
{
    /**
     * The module object.
     *
     * @var Extension
     */
    protected $module;

    /**
     * Create a new ExtensionWasDisabled instance.
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
