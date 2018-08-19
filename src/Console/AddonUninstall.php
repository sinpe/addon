<?php

namespace Sinpe\Addon\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Sinpe\Addon\Collection;
use Sinpe\Addon\Extension\Extension;
use Sinpe\Addon\Extension\ExtensionManager;
use Sinpe\Addon\Module\Module;
use Sinpe\Addon\Module\ModuleManager;

/**
 * Class AddonUninstall.
 */
class AddonUninstall extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'addon:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall an addon.';

    /**
     * Execute the console command.
     *
     * @param Collection       $addons
     * @param ModuleManager    $modules
     * @param ExtensionManager $extensions
     */
    public function handle(Collection $addons, ModuleManager $modules, ExtensionManager $extensions)
    {
        if (!$addon = $addons->get($this->argument('addon'))) {
            $this->error('The ['.$this->argument('addon').'] could not be found.');
        }

        if ($addon instanceof Module) {
            $modules->uninstall($addon);

            $this->info('The ['.$this->argument('addon').'] module was uninstalled.');
        }

        if ($addon instanceof Extension) {
            $extensions->uninstall($addon);

            $this->info('The ['.$this->argument('addon').'] extension was uninstalled.');
        }
    }

    /**
     * Get the command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['addon', InputArgument::OPTIONAL, 'The addon to uninstall.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['seed', null, InputOption::VALUE_NONE, 'Seed the addon after installing?'],
        ];
    }
}
