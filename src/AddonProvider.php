<?php

namespace Sinpe\Addon;

use Closure;

/**
 * Class AddonProvider.
 *
 * Addon接入类
 */
class AddonProvider
{
    /**
     * The addon commands.
     *
     * @var array
     */
    //protected $commands = [];

    /**
     * The addon command schedules.
     *
     * @var array
     */
    //protected $schedules = [];

    /**
     * The addon manager instance.
     *
     * @var AddonCriteriaInterface
     */
    protected $manager;

    /**
     * 系统集成时，当有部分功能需要结合调用系统来实现时，通过传入垫片来实现.
     *
     * @var Closure
     */
    protected $polyfill;

    /**
     * Create a new AddonProvider instance.
     *
     * @param AddonCriteriaInterface $manager  criteria
     * @param Closure                $polyfill 系统集成时特殊对接垫片
     */
    final public function __construct(
        AddonCriteriaInterface $manager,
        Closure $polyfill = null
    ) {
        $this->manager = $manager;

        if (!is_null($polyfill)) {
            $this->polyfill = $polyfill->bindTo($this, get_class($this));
        }
    }

    /**
     * Get the addon commands.
     *
     * @return array
     */
    /*
    public function getCommands()
    {
        return $this->commands;
    }
    */

    /**
     * Get the addon command schedules.
     *
     * @return array
     */
    /*
    public function getSchedules()
    {
        return $this->schedules;
    }
    */
    // bind($container)
    // registerMiddlewares($app)
    // registerRoutes($router)
    // registerEvents($event)

    /**
     * Addon的接入内容.
     */
    final public function register()
    {
        // 根据不同应用模式调用不同注册过程
        $method = 'register'.studly_case($this->manager->mode);

        if (!method_exists($this, $method)) {
            throw new \BadMethodCallException(
                sprintf(
                    'Method "%s::%s" does not exist.',
                    get_class($this),
                    $method
                )
            );
        }

        $this->{$method}();
    }

    /**
     * 运行垫片程序，在哪个位置调用由实现子类决定.
     *
     * @return mixed
     */
    protected function runPolyfill()
    {
        if ($this->polyfill) {
            return ($this->polyfill)();
        }
    }

    /**
     * Call a method.
     *
     * @param string $method    方法
     * @param array  $arguments 参数
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        // 调用方法
        if (is_callable([$this->manager, $method])) {
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
}
