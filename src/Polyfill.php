<?php

namespace Sinpe\Addon;

use Psr\Container\ContainerInterface;

use Sinpe\Event\EventManager;
use Sinpe\Event\EventManagerInterface;
use Sinpe\Config\ConfigInterface;


/**
 * 系统对接集成扩展基类.
 *
 * @author Sinpe, Inc.
 * @author 18222544@qq.com
 */
abstract class Polyfill implements PolyfillInterface
{
    /**
     * 依赖容器.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * 事件管理器.
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * 配置对象
     *
     * @var ConfigInterface
     */
    protected $config;

    /**
     * 构造器.
     */
    public function __construct(
        ContainerInterface $container,
        ConfigInterface $config
    ) {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Get container instance.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get event manager instance.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = new EventManager();
        }

        return $this->eventManager;
    }

    /**
     * 获得配置项.
     *
     * @param string $key     键
     * @param mixed  $default 缺省值
     *
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        // 直接取值
        return $this->config->get($key, $default);
    }

    /**
     * 设置配置项.
     *
     * @param string $key   键
     * @param mixed  $value 值
     *
     * @return mixed
     */
    public function setConfig(string $key, $value)
    {
        return $this->config->set($key, $value);
    }

    /**
     * 检查配置项.
     *
     * @param string $key 键
     *
     * @return mixed
     */
    public function hasConfig(string $key)
    {
        return $this->config->has($key);
    }
}
