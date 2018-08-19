<?php

namespace Sinpe\Addon\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Sinpe\Addon\Loader;
use Sinpe\Addon\Manager;
use Sinpe\Addon\Console\Command\MakeAddonPaths;
use Sinpe\Addon\Console\Command\ScaffoldTheme;
use Sinpe\Addon\Console\Command\WriteAddonButtonLang;
use Sinpe\Addon\Console\Command\WriteAddonClass;
use Sinpe\Addon\Console\Command\WriteAddonComposer;
use Sinpe\Addon\Console\Command\WriteAddonFeatureTest;
use Sinpe\Addon\Console\Command\WriteAddonFieldLang;
use Sinpe\Addon\Console\Command\WriteAddonGitIgnore;
use Sinpe\Addon\Console\Command\WriteAddonLang;
use Sinpe\Addon\Console\Command\WriteAddonPermissionLang;
use Sinpe\Addon\Console\Command\WriteAddonPermissions;
use Sinpe\Addon\Console\Command\WriteAddonPhpUnit;
use Sinpe\Addon\Console\Command\WriteAddonSectionLang;
use Sinpe\Addon\Console\Command\WriteAddonServiceProvider;
use Sinpe\Addon\Console\Command\WriteAddonStreamLang;

/**
 * Class MakeAddon.
 */
class MakeAddon extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:addon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new addon.';

    /**
     * Execute the console command.
     *
     * @param Manager    $addons
     * @param Loader     $loader
     * @param Repository $config
     *
     * @throws \Exception
     */
    public function handle(Manager $addons, Loader $loader, Repository $config)
    {
        $namespace = $this->argument('namespace');

        if (preg_match('#^[a-zA-Z0-9_]+\.[a-zA-Z_]+\.[a-zA-Z0-9_]+\z#u', $namespace) !== 1) {
            throw new \Exception('The namespace should be snake case and formatted like: {vendor}.{type}.{slug}');
        }

        list($vendor, $type, $slug) = array_map(
            function ($value) {
                return str_slug(strtolower($value), '_');
            },
            explode('.', $namespace)
        );

        if (!in_array($type, $config->get('streams::addons.types'))) {
            throw new \Exception("The [$type] addon type is invalid.");
        }

        $type = str_singular($type);

        $path = $this->dispatch(new MakeAddonPaths($vendor, $type, $slug, $this));

        $this->dispatch(new WriteAddonLang($path, $type, $slug));
        $this->dispatch(new WriteAddonClass($path, $type, $slug, $vendor));
        $this->dispatch(new WriteAddonPhpUnit($path, $type, $slug, $vendor));
        $this->dispatch(new WriteAddonComposer($path, $type, $slug, $vendor));
        // @todo Autoloading issues...
        //$this->dispatch(new WriteAddonTestCase($path, $type, $slug, $vendor));
        $this->dispatch(new WriteAddonGitIgnore($path, $type, $slug, $vendor));
        $this->dispatch(new WriteAddonFeatureTest($path, $type, $slug, $vendor));
        $this->dispatch(new WriteAddonServiceProvider($path, $type, $slug, $vendor));

        $this->info("Addon [{$vendor}.{$type}.{$slug}] created.");

        $loader
            ->load($path)
            ->register()
            ->dump();

        $addons->register();

        /*
         * Create the initial migration file
         * for modules and extensions.
         */
        if (in_array($type, ['module', 'extension']) || $this->option('migration')) {
            $this->call(
                'make:migration',
                [
                    'name' => 'create_'.$slug.'_fields',
                    '--addon' => $namespace,
                    '--fields' => true,
                ]
            );
        }

        /*
         * Scaffold Modules and Extensions.
         */
        if (in_array($type, ['module', 'extension'])) {
            $this->dispatch(new WriteAddonFieldLang($path));
            $this->dispatch(new WriteAddonStreamLang($path));
            $this->dispatch(new WriteAddonPermissions($path));
            $this->dispatch(new WriteAddonPermissionLang($path));
        }

        /*
         * Scaffold Modules.
         */
        if ($type == 'module') {
            $this->dispatch(new WriteAddonButtonLang($path));
            $this->dispatch(new WriteAddonSectionLang($path));
        }

        /*
         * Scaffold themes.
         *
         * This moves in Bootstrap 3
         * Font-Awesome and jQuery.
         */
        if ($type == 'theme') {
            $this->dispatch(new ScaffoldTheme($path));
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['namespace', InputArgument::REQUIRED, 'The addon\'s desired dot namespace.'],
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
            ['shared', null, InputOption::VALUE_NONE, 'Indicates if the addon should be created in shared addons.'],
            ['migration', null, InputOption::VALUE_NONE, 'Indicates if a fields migration should be created.'],
        ];
    }
}
