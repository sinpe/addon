<?php

namespace Sinpe\Addon\Extension\Event;

use Sinpe\Addon\Extension\Extension;

/**
 * Class ExtensionWasUninstalled.
 */
class ExtensionWasUninstalled
{
    /**
     * The extension object.
     *
     * @var \Sinpe\Addon\Extension\Extension
     */
    protected $extension;

    /**
     * Create a new ExtensionWasUninstalled instance.
     *
     * @param Extension $extension
     */
    public function __construct(Extension $extension)
    {
        $this->extension = $extension;
    }

    /**
     * Get the extension object.
     *
     * @return Extension
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
