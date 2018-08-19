<?php

namespace Sinpe\Addon\Theme;

use Sinpe\Addon\Collection as CollectionBase;

/**
 * Class Collection.
 */
class Collection extends CollectionBase
{
    /**
     * Return the active theme.
     *
     * @return Theme
     */
    public function active($type = null)
    {
        if (!$type) {
            return $this->current();
        }

        $admin = $type == 'standard' ? false : true;

        /* @var Theme $item */
        foreach ($this->items as $item) {
            if ($item->isActive() && $item->isAdmin() === $admin) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Return the current theme.
     *
     * @return null|Theme
     */
    public function current()
    {
        /* @var Theme $item */
        foreach ($this->items as $item) {
            if ($item->isCurrent()) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Return only non-admin themes.
     *
     * @return Collection
     */
    public function standard()
    {
        $items = [];

        /* @var Theme $item */
        foreach ($this->items as $item) {
            if (!$item->isAdmin()) {
                $items[] = $item;
            }
        }

        return new static($items);
    }

    /**
     * Return only admin themes.
     *
     * @return Collection
     */
    public function admin()
    {
        $items = [];

        /* @var Theme $item */
        foreach ($this->items as $item) {
            if ($item->isAdmin()) {
                $items[] = $item;
            }
        }

        return new static($items);
    }
}
