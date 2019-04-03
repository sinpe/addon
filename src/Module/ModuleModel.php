<?php

namespace Sinpe\Addon\Module;

use Sinpe\Eloquent\Collection;
use Sinpe\Eloquent\Model as Base;

/**
 * Class Entity.
 */
class ModuleModel extends Base
{
    /**
     * Define the table name.
     *
     * @var string
     */
    protected $table = 'addons_modules';

    /**
     * Find a module by it's namespace or return a new
     * module with the given namespace.
     *
     * @param  $namespace
     *
     * @return Entity
     */
    public function findByNamespaceOrNew($namespace)
    {
        $module = $this->findByNamespace($namespace);

        if ($module instanceof Entity) {
            return $module;
        }

        $module = $this->newInstance();

        $module->namespace = $namespace;

        $module->save();

        return $module;
    }

    /**
     * Find a module by it's namespace.
     *
     * @param  $namespace
     *
     * @return null|Entity
     */
    public function findByNamespace($namespace)
    {
        return $this->where('namespace', $namespace)->first();
    }

    /**
     * Get all enabled module namespaces.
     *
     * @return Collection
     */
    public function getEnabledNamespaces()
    {
        return $this->where('installed', true)->where('enabled', true)->get()->pluck('namespace');
    }

    /**
     * Get all installed module namespaces.
     *
     * @return Collection
     */
    public function getInstalledNamespaces()
    {
        return $this->where('installed', true)->get()->pluck('namespace');
    }

}
