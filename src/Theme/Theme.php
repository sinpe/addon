<?php

namespace Sinpe\Addon\Theme;

use Sinpe\Addon\Addon;

/**
 * Class Theme.
 */
class Theme extends Addon
{
    /**
     * Determines whether this is
     * an admin theme or not.
     *
     * @var bool
     */
    protected $admin = false;

    /**
     * Determines whether this is
     * the active theme or not.
     *
     * @var bool
     */
    protected $active = false;

    /**
     * Determines whether this is
     * the currently rendering theme
     * or not.
     *
     * @var bool
     */
    protected $current = false;

    /**
     * Get the admin flag.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the active flag.
     *
     * @param  $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the active flag.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Return the current flag.
     *
     * @return bool
     */
    public function isCurrent()
    {
        return $this->current;
    }

    /**
     * Set the current flag.
     *
     * @param $current
     *
     * @return $this
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get the module's presenter.
     *
     * @return Presenter
     */
    public function getPresenter()
    {
        return $this->manager->getContainer()->make(Presenter::class, ['object' => $this]);
    }
}
