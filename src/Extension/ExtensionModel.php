<?php

namespace Sinpe\Addon\Extension;

use Sinpe\Eloquent\Collection;
use Sinpe\Eloquent\Model as Base;

/**
 * Class Entity.
 */
class ExtensionModel extends Base
{
    /**
     * Define the table name.
     *
     * @var string
     */
    protected $table = 'addons_extensions';

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
}
