<?php

namespace Sinpe\Addon\Extension;

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
    protected $table = 'addons_extensions';

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
     * Find a extension by it's namespace or return a new
     * extension with the given namespace.
     *
     * @param  $namespace
     *
     * @return ExtensionModel
     */
    public function findByNamespaceOrNew($namespace)
    {
        $extension = $this->findByNamespace($namespace);

        if ($extension instanceof ExtensionModel) {
            return $extension;
        }

        $extension = $this->newInstance();

        $extension->namespace = $namespace;

        $extension->save();

        return $extension;
    }

    /**
     * Find a extension by it's namespace.
     *
     * @param  $namespace
     *
     * @return mixed
     */
    public function findByNamespace($namespace)
    {
        return $this->where('namespace', $namespace)->first();
    }

    /**
     * Get all enabled extension namespaces.
     *
     * @return Collection
     */
    public function getEnabledNamespaces()
    {
        return $this->where('enabled', true)->get()->pluck('namespace');
    }

    /**
     * Get all installed extension namespaces.
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
