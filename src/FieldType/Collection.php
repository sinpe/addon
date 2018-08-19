<?php

namespace Sinpe\Addon\FieldType;

use Sinpe\Addon\Collection as CollectionBase;

/**
 * Class FieldTypeCollection.
 */
class Collection extends CollectionBase
{
    /**
     * Get a field type from the
     * collection by namespace key.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return null|FieldType
     */
    public function get($key, $default = null)
    {
        $type = parent::get($key, $default);

        if (!$type) {
            return null;
        }

        return clone $type;
    }

    /**
     * Find an addon by it's slug.
     *
     * @param  $slug
     *
     * @return null|FieldType
     */
    public function findBySlug($slug)
    {
        /* @var FieldType $item */
        foreach ($this->items as $item) {
            if ($item->getSlug() == $slug) {
                return clone $item;
            }
        }

        return null;
    }
}
