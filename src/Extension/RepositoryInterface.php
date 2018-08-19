<?php

namespace Sinpe\Addon\Extension;

use Sinpe\Eloquent\Collection;

/**
 * Interface RepositoryInterface.
 */
interface RepositoryInterface
{
    /**
     * Return all extensions in the database.
     *
     * @return Collection
     */
    public function all();

    /**
     * Create a extension record.
     *
     * @param Extension $extension
     *
     * @return bool
     */
    public function create(Extension $extension);

    /**
     * Delete a extension record.
     *
     * @param Extension $extension
     *
     * @return ExtensionModel
     */
    public function delete(Extension $extension);

    /**
     * Mark a extension as installed.
     *
     * @param Extension $extension
     *
     * @return bool
     */
    public function install(Extension $extension);

    /**
     * Mark a extension as uninstalled.
     *
     * @param Extension $extension
     *
     * @return bool
     */
    public function uninstall(Extension $extension);

    /**
     * Mark a extension as disabled.
     *
     * @param Extension $extension
     *
     * @return bool
     */
    public function disable(Extension $extension);

    /**
     * Mark a extension as enabled.
     *
     * @param Extension $extension
     *
     * @return bool
     */
    public function enabled(Extension $extension);
}
