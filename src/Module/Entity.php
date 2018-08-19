<?php

namespace Sinpe\Addon\Module;

use Sinpe\Eloquent\Collection;
use Sinpe\Eloquent\Model as ModelBase;
use Sinpe\Eloquent\Observer;

/**
 * Class Entity.
 */
class Entity extends ModelBase
{
    /**
     * Define the table name.
     *
     * @var string
     */
    protected $table = 'addons_modules';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        // TODO 缓存
        self::observe(new Observer()); // TODO

        parent::boot();
    }

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

    /**
     * Return a new collection.
     *
     * @param array $items
     *
     * @return Collection
     */
    public function newCollection(array $items = [])
    {
        return new Collection($items);
    }
}
