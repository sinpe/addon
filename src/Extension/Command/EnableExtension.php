<?php

namespace Sinpe\Addon\Extension\Command;

use Sinpe\Addon\Extension\RepositoryInterface;
use Sinpe\Addon\Extension\Event\ExtensionWasEnabled;
use Sinpe\Addon\Extension\Extension;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class EnableExtension.
 */
class EnableExtension
{
    /**
     * The extension to uninstall.
     *
     * @var Extension
     */
    protected $extension;

    /**
     * Create a new EnableExtension instance.
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
        $extensions->enabled($this->extension);

        $events->fire(new ExtensionWasEnabled($this->extension));

        return true;
    }
}
