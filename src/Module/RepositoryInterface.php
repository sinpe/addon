<?php

namespace Sinpe\Addon\Module;

use Sinpe\Eloquent\Collection;

/**
 * Interface RepositoryInterface.
 */
interface RepositoryInterface
{
    /**
     * Return all modules in the database.
     *
     * @return Collection
     */
    public function all();

    /**
     * Create a module record.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function create(Module $module);

    /**
     * Delete a module record.
     *
     * @param Module $module
     *
     * @return Entity
     */
    public function delete(Module $module);

    /**
     * Mark a module as installed.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function install(Module $module);

    /**
     * Mark a module as uninstalled.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function uninstall(Module $module);

    /**
     * Mark a module as disabled.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function disable(Module $module);

    /**
     * Mark a module as enabled.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function enabled(Module $module);
}
