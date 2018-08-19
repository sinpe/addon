<?php

namespace Sinpe\Addon\Extension\Command;

use Sinpe\Addon\Manager;
use Sinpe\Addon\Extension\RepositoryInterface;
use Sinpe\Addon\Extension\Event\ExtensionWasInstalled;
use Sinpe\Addon\Extension\Extension;
use Anomaly\Streams\Platform\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Class InstallExtension.
 */
class InstallExtension
{
    /**
     * The seed flag.
     *
     * @var bool
     */
    protected $seed;

    /**
     * The extension to install.
     *
     * @var Extension
     */
    protected $extension;

    /**
     * Create a new InstallExtension instance.
     *
     * @param Extension $extension
     * @param bool      $seed
     */
    public function __construct(Extension $extension, $seed = false)
    {
        $this->seed = $seed;
        $this->extension = $extension;
    }

    /**
     * Handle the command.
     *
     * @param InstallExtension|Kernel $console
     * @param Manager                 $manager
     * @param Dispatcher              $dispatcher
     * @param RepositoryInterface     $extensions
     *
     * @return bool
     */
    public function handle(
        Kernel $console,
        Manager $manager,
        Dispatcher $dispatcher,
        RepositoryInterface $extensions
    ) {
        $this->extension->fire('installing');

        $options = [
            '--addon' => $this->extension->getNamespace(),
            '--force' => true,
        ];

        $console->call('migrate', $options);

        $extensions->install($this->extension);

        $manager->register();

        if ($this->seed) {
            $console->call('db:seed', $options);
        }

        $this->extension->fire('installed');

        $dispatcher->fire(new ExtensionWasInstalled($this->extension));

        return true;
    }
}
