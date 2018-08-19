<?php

namespace Sinpe\Addon\Extension\Event;

use Sinpe\Addon\Extension\Extension;

/**
 * Class ExtensionWasRegistered.
 */
class ExtensionWasRegistered
{
    /**
     * The extension object.
     *
     * @var Extension
     */
    protected $extension;

    /**
     * Create a new ExtensionWasRegistered instance.
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
