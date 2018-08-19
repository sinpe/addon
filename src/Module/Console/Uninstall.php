<?php

namespace Sinpe\Addon\Module\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Exception;
use Sinpe\Addon\Module\Module;
use Sinpe\Addon\Module\ModuleCollection;
use Sinpe\Addon\Module\ModuleManager;

/**
 * Class Uninstall.
 */
class Uninstall extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall a module.';

    /**
     * Execute the console command.
     *
     * @param ModuleManager    $manager
     * @param ModuleCollection $modules
     */
    public function handle(ModuleManager $manager, ModuleCollection $modules)
    {
        /* @var Module $module */
        $module = $modules->get($this->argument('module'));

        if (!$module) {
            throw new Exception('Module '.$this->argument('module').' does not exist or is not installed.');
        }

        $manager->uninstall($module);

        $this->info(trans($module->getName()).' uninstalled successfully!');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The module\'s dot namespace.'],
        ];
    }
}