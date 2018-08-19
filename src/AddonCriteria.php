<?php

namespace Sinpe\Addon;

/**
 * Class Criteria.
 *
 * 用来过滤manager在addon的可使用功能
 */
class AddonCriteria implements AddonCriteriaInterface
{
    /**
     * 可用方法，具体需要在每个addon的子类中指定.
     *
     * @var array
     */
    protected static $unguarded = [];

    /**
     * Addon type.
     *
     * @var string
     */
    private $addonType;

    /**
     * 白名单方法.
     *
     * @var array
     */
    private $whitelists = [
        'getEventManager',
    ];

    /**
     * 不可用方法.
     *
     * @var array
     */
    private $invalids = [];

    /**
     * The manager.
     *
     * @var ManagerInterface
     */
    private $manager;

    /**
     * 构造函数.
     *
     * @param string           $addonType addon类型
     * @param ManagerInterface $manager   Manager
     */
    public function __construct(
        string $addonType,
        ManagerInterface $manager
    ) {
        $this->addonType = $addonType;
        $this->manager = $manager;
        // 通过在manager设置whitelists允许所有addon调用
        if (call_user_func(get_class($this->manager).'::hasMacro', 'whitelists')) {
            // 白名单
            $this->whitelists = $this->manager->runMacro('whitelists');
        }
        // 通过在manager设置invalids禁止所有addon调用
        if (call_user_func(get_class($this->manager).'::hasMacro', 'invalids')) {
            // 禁止调用的
            $this->invalids = $this->manager->runMacro('invalids');
        }
    }

    /**
     * 判断是否可用.
     *
     * @param string $name 方法名
     *
     * @return bool
     */
    protected function methodIsSafe(string $name)
    {
        $valid = in_array($name, static::$unguarded)
            || in_array($name, $this->whitelists);

        return $valid && !in_array($name, $this->invalids);
    }

    /**
     * 调用manager方法.
     *
     * @param string $method    方法名
     * @param array  $arguments 方法参数
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if ($this->methodIsSafe($method)) {
            return call_user_func_array([$this->manager, $method], $arguments);
        }

        throw new \BadMethodCallException(
            sprintf(
                'Method "%s::%s" does not exist.',
                get_class($this),
                $method
            )
        );
    }

    /**
     * Properties.
     *
     * @param string $name
     */
    public function __get($name)
    {
        return $this->manager->{$name};
    }
}
