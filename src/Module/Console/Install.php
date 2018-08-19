<?php

namespace Sinpe\Addon\Module\Console;

use Sinpe\Addon\Module\Module;
use Sinpe\Addon\Module\ModuleCollection;
use Sinpe\Addon\Module\ModuleManager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Install.
 */
class Install extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a module.';

    /**
     * Execute the console command.
     *
     * @param ModuleManager    $manager
     * @param ModuleCollection $modules
     *
     * @throws \Exception
     */
    public function handle(ModuleManager $manager, ModuleCollection $modules)
    {
        /* @var Module $module */
        $module = $modules->get($this->argument('module'));

        if (!$module) {
            throw new \Exception('Module ['.$this->argument('module').'] does not exist.');
        }

        $manager->install($module, $this->option('seed'));

        $this->info(trans($module->getName()).' installed successfully!');
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

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['seed', null, InputOption::VALUE_NONE, 'Seed the module after installing?'],
        ];
    }
}
