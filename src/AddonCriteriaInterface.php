<?php

namespace Sinpe\Addon;

/**
 * Interface AddonCriteria.
 *
 * 桥接
 */
interface AddonCriteriaInterface
{
    /**
     * 调用manager方法.
     *
     * @param string $method    方法名
     * @param array  $arguments 方法参数
     *
     * @return mixed
     */
    public function __call($method, $arguments);
}
