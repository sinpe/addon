<?php

namespace Sinpe\Addon\Module;

use Anomaly\Streams\Platform\Support\Authorizer;
use Sinpe\Addon\Collection as CollectionBase;

/**
 * Class Collection.
 */
class Collection extends CollectionBase
{
    /**
     * Return navigate-able modules.
     *
     * @return ModuleCollection
     */
    public function navigation()
    {
        $navigation = [];

        /* @var Module $item */
        foreach ($this->items as $item) {
            if ($item->getNavigation()) {
                $navigation[trans($item->getName())] = $item;
            }
        }

        ksort($navigation);

        foreach ($navigation as $key => $item) {
            if ($item->getNamespace() == 'anomaly.module.dashboard') {
                $navigation = [$key => $item] + $navigation;

                break;
            }
        }

        return self::make($navigation)
            ->enabled()
            ->accessible();
    }

    /**
     * Return accessible modules.
     *
     * @return ModuleCollection
     */
    public function accessible()
    {
        $accessible = [];

        /* @var Authorizer $authorizer */
        $authorizer = app('Anomaly\Streams\Platform\Support\Authorizer');

        /* @var Module $item */
        foreach ($this->items as $item) {
            if ($authorizer->authorize($item->getNamespace('*'))) {
                $accessible[] = $item;
            }
        }

        return self::make($accessible);
    }

    /**
     * Return the active module.
     *
     * @return Module
     */
    public function active()
    {
        /* @var Module $item */
        foreach ($this->items as $item) {
            if ($item->isActive()) {
                return $item;
            }
        }

        return null;
    }
}
