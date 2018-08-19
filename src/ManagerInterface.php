<?php

namespace Sinpe\Addon;

/**
 * Interface Manager.
 *
 * 管理器接口
 *
 * @author Sinpe Inc. <support@sinpe.com>
 * @author WuPinlong <18222544@qq.com>
 */
interface ManagerInterface
{
    /**
     * 允许外部调用覆盖部分预定义的映射.
     *
     * @return array
     */
    //public function maps($overrides = []);

    /**
     * 获取addon命名空间或者给key附加addon的命名空间.
     *
     * @param Option $addonInfo addon信息集
     * @param string $key       待添加addon命名空间的key
     *
     * @return string
     */
    public function getAddonNamespace(Option $addonInfo, string $key = null);

    /**
     * 管理器执行addon的注册.
     *
     * @param bool $reload 是否重新导入addon，比如开发模式时可以设置为true，以达到每次正确加载
     */
    public function register($reload = false);
}
