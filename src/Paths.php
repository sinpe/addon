<?php

namespace Sinpe\Addon;

/**
 * Class Paths.
 *
 * Addon存放路径管理器，通过该对象输出所有固定目录或者配置目录下的Addon绝对路径
 *
 * 例子：
 * path方式（配置项addons.paths）：
 *      <path>/<vendor>/<slug>-<type>
 * directory方式（配置项addons.directories）:
 *      <directory> // 目录下的结构是<vendor>/<slug>-<type>
 */
class Paths
{
    /**
     * The manager.
     *
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * Create a new Paths instance.
     *
     * @param ManagerInterface $manager addon管理器
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Return all addon paths in a given folder.
     *
     * @return array
     */
    public function all()
    {
        // 返回各addon的绝对路径
        return array_unique(
            array_merge(
                $this->core(),
                $this->configured()
            )
        );
    }

    /**
     * 返回addons目录下的addon路径.
     *
     * @return bool
     */
    public function core()
    {
        // 获取addons文件夹的绝对路径，该文件夹下存放各类addon，其下结构是vendor/slug-type
        return $this->paths($this->manager->getBasePath('addons'));
    }

    /**
     * 获取某个目录下的addon路径.
     *
     * @param string $dir 目录
     *
     * @return array
     */
    protected function paths(string $dir)
    {
        if (!is_dir($dir)) {
            return [];
        }

        $dir = str_replace('\\', '/', $dir);

        $paths = [];

        foreach (glob("{$dir}/*", GLOB_ONLYDIR) as $dir) { // vendor
            foreach (glob("{$dir}/*", GLOB_ONLYDIR) as $path) { // slug-type
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * 通过配置设置的addon路径.
     *
     * 例子：
     * 相对于系统部署目录
     * 'addons.paths' => [
     *      'lib/addons/sinpe/example-module',
     *      'vendor/addons/baidu/face-module'
     * ]
     *
     * 'addons.directories' => [
     *      '/www/addons1/',
     *      '/www/addons2'
     * ]
     *
     * @return array|bool
     */
    protected function configured()
    {
        // 直接是addon路径
        $paths = array_map(
            \Closure::bind(
                function ($path) {
                    $path = str_replace('\\', '/', $path);

                    return $this->getBasePath($path);
                },
                $this->manager
            ),
            // [
            //    "<path>/<vendor>/<slug>-<type>",
            //    "<path>/<vendor>/<slug>-<type>"
            //]
            $this->manager->getConfig('addons.paths', [])
        );

        // 根据存放目录读取addon路径，区分vendor
        // [
        //    "<directory>",
        //    "<directory>"
        //]
        foreach ($this->manager->getConfig('addons.directories', []) as $directory) {
            $paths = array_merge(
                $paths,
                $this->paths(trim($directory, '\\/'))
            );
        }

        return array_unique(array_filter($paths));
    }
}
