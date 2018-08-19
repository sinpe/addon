<?php

namespace Sinpe\Addon\Extension\Event;

use Sinpe\Addon\Extension\Extension;

/**
 * Class ExtensionWasInstalled.
 */
class ExtensionWasInstalled
{
    /**
     * The extension object.
     *
     * @var \Sinpe\Addon\Extension\Extension
     */
    protected $extension;

    /**
     * Create a new ExtensionWasInstalled instance.
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
