<?php

namespace Sinpe\Addon;

/**
 * Class Collection.
 */
class Collection extends \Illuminate\Support\Collection
{
    /**
     * Create a new Collection instance.
     *
     * @param array $items 集合
     */
    public function __construct($items = [])
    {
        foreach ($items as $key => $item) {
            $this->items[$item->addonId] = $item;
        }
    }

    /**
     * Return all addon namespaces.
     *
     * @param null $key 键
     *
     * @return array
     */
    public function namespaces($key = null)
    {
        return array_values(
            $this->map(
                function ($addon) use ($key) {
                    return $addon->addonId.($key ? '::'.$key : '');
                }
            )->all()
        );
    }

    /**
     * Get an addon.
     *
     * @param mixed $key     名称
     * @param null  $default 默认值
     *
     * @return Addon|mixed|null
     */
    public function get($key, $default = null)
    {
        if (!$key) {
            return $default;
        }

        return parent::get($key, $default);
    }

    /**
     * Sort through each item with a callback.
     *
     * @param callable|null $callback 排序方法
     *
     * @return static
     */
    public function sort(callable $callback = null)
    {
        return parent::sort(
            $callback ?: function ($a, $b) {
                if ($a->slug == $b->slug) {
                    return 0;
                }

                return ($a->slug < $b->slug) ? -1 : 1;
            }
        );
    }

    /**
     * Order addon's by their slug.
     *
     * @param string $direction 排序
     *
     * @return Collection
     */
    public function orderBySlug(string $direction = 'asc')
    {
        return $this->sort(
            function ($a, $b) use ($direction) {
                if ($a->slug == $b->slug) {
                    return 0;
                }
                if ($direction == 'asc') {
                    return ($a->slug < $b->slug) ? -1 : 1;
                } else {
                    return ($a->slug > $b->slug) ? -1 : 1;
                }
            }
        );
    }

    /**
     * Return only installable addons.
     *
     * @return Collection
     */
    public function installable()
    {
        return $this->filter(
            function ($addon) {
                return in_array(
                    $addon->type,
                    [Manager::ADDON_TYPE_M, Manager::ADDON_TYPE_E]
                );
            }
        );
    }

    /**
     * Return enabled addons.
     *
     * @return Collection
     */
    public function enabled()
    {
        return $this->installable()->filter(
            function ($addon) {
                /* @var Module|Extension $addon */
                return $addon->enabled;
            }
        );
    }

    /**
     * Return installed addons.
     *
     * @return Collection
     */
    public function installed()
    {
        return $this->installable()->filter(
            function ($addon) {
                /* @var Module|Extension $addon */
                return $addon->installed;
            }
        );
    }

    /**
     * Return uninstalled addons.
     *
     * @return Collection
     */
    public function uninstalled()
    {
        return $this->installable()->filter(
            function ($addon) {
                /* @var Module|Extension $addon */
                return !$addon->installed;
            }
        );
    }
}
