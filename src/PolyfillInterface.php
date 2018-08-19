<?php

namespace Sinpe\Addon;

use Sinpe\Support\ContainerInterface;
use Sinpe\Event\EventManagerInterface;

/**
 * 系统对接集成扩展接口.
 */
interface PolyfillInterface
{
    /**
     * Get container instance.
     *
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * Get event manager instance.
     *
     * @return EventManagerInterface
     */
    public function getEventManager();

    /**
     * Get debug status.
     *
     * @return bool
     */
    public function isDebug();

    /**
     * 获得系统安装路径（绝对路径）.
     *
     * 比如：APP_ROOT . $path
     *
     * @param string $path 相对路径
     *
     * @return string
     */
    public function getBasePath(string $path = '');

    /**
     * 获得配置项.
     *
     * @param string $key     键
     * @param mixed  $default 缺省值
     *
     * @return mixed
     */
    public function getConfig(string $key, $default = null);

    /**
     * 设置配置项.
     *
     * @param string $key   键
     * @param mixed  $value 值
     *
     * @return mixed
     */
    public function setConfig(string $key, $value);

    /**
     * 检查配置项.
     *
     * @param string $key 键
     *
     * @return mixed
     */
    public function hasConfig(string $key);

    /**
     * 获取用于覆盖addon包自带配置的配置，
     * 比如：<root>/configs/addons/sinpe/example-module的配置内容.
     *
     * @param string $vendor    厂家名称，比如：sinpe
     * @param string $addonName addon名称，比如：example-module
     *
     * @return array
     */
    public function getConfigOverrides(string $vendor, string $addonName);

    /**
     * 获取addon需要的polyfill配置.
     *
     * @param string $vendor    厂家名称，比如：sinpe
     * @param string $addonName addon名称，比如：example-module
     * @param string $flag      标志，值：register、init
     *
     * @return array
     */
    public function getPolyfill(string $vendor, string $addonName, string $flag);
}
