<?php

namespace Sinpe\Addon\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Sinpe\Addon\Collection;
use Sinpe\Addon\Console\Command\PublishConfig;
use Sinpe\Addon\Console\Command\PublishTranslations;
use Sinpe\Addon\Console\Command\PublishViews;

/**
 * Class AddonPublish.
 */
class AddonPublish extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'addon:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish an the configuration and translations for an addon.';

    /**
     * Execute the console command.
     */
    public function handle(Collection $addons)
    {
        if (!$this->argument('addon')) {
            foreach ($addons as $addon) {
                $this->call(
                    'addon:publish',
                    [
                        'addon' => $addon->getNamespace(),
                    ]
                );
            }

            return;
        }

        $addon = $addons->get($this->argument('addon'));

        $this->dispatch(new PublishViews($addon, $this));
        $this->dispatch(new PublishConfig($addon, $this));
        $this->dispatch(new PublishTranslations($addon, $this));
    }

    /**
     * Get the command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['addon', InputArgument::OPTIONAL, 'The addon to publish. Omit to publish all addons.'],
        ];
    }

    /**
     * Get the command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force overwriting files.'],
        ];
    }
}
