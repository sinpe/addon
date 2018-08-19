<?php

namespace Sinpe\Addon\Extension\Command;

use Sinpe\Addon\Extension\RepositoryInterface;
use Sinpe\Addon\Extension\Event\ExtensionWasDisabled;
use Sinpe\Addon\Extension\Extension;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class DisableExtension.
 */
class DisableExtension
{
    /**
     * The extension to uninstall.
     *
     * @var Extension
     */
    protected $extension;

    /**
     * Create a new DisableExtension instance.
     *
     * @param Extension $extension
     */
    public function __construct(Extension $extension)
    {
        $this->extension = $extension;
    }

    /**
     * Handle the command.
     *
     * @param RepositoryInterface $extensions
     * @param Dispatcher          $events
     *
     * @return bool
     */
    public function handle(RepositoryInterface $extensions, Dispatcher $events)
    {
        $extensions->disable($this->extension);

        $events->fire(new ExtensionWasDisabled($this->extension));

        return true;
    }
}
