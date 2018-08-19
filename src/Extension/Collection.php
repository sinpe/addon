<?php

namespace Sinpe\Addon\Extension;

use Sinpe\Addon\Collection as CollectionBase;

/**
 * Class ExtensionCollection.
 */
class Collection extends CollectionBase
{
    /**
     * Search for and return matching extensions.
     *
     * @param mixed $pattern
     * @param bool  $strict
     *
     * @return Collection
     */
    public function search($pattern, $strict = false)
    {
        $matches = [];

        foreach ($this->items as $item) {
            /* @var Extension $item */
            if (str_is($pattern, $item->getProvides())) {
                $matches[] = $item;
            }
        }

        return self::make($matches);
    }

    /**
     * Get an extension by it's reference.
     *
     * Example: extension.users::authenticator.default
     *
     * @param mixed $key
     *
     * @return null|Extension
     */
    public function find($key)
    {
        foreach ($this->items as $item) {
            /* @var Extension $item */
            if ($item->getProvides() == $key) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Return the active extension.
     *
     * @return Extension
     */
    public function active()
    {
        foreach ($this->items as $item) {
            /* @var Extension $item */
            if ($item->isActive()) {
                return $item;
            }
        }

        return null;
    }
}
