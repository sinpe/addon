<?php

namespace Sinpe\Addon;

use Closure;
//use Illuminate\Foundation\Bus\DispatchesJobs;

//use Robbo\Presenter\PresentableInterface;
//use Robbo\Presenter\Presenter;
use Sinpe\Support\Traits\FireAware as FireTrait;

/**
 * Class Addon.
 */
class Addon implements AddonInterface //, PresentableInterface
{
    use FireTrait;
    //use DispatchesJobs;

    /**
     * Runtime cache.
     *
     * @var array
     */
    protected $cache = [];

    /**
     * The addon path.
     *
     * @var string
     */
    protected $path = null;

    /**
     * The addon id.
     *
     * @var string
     */
    protected $addonId = null;

    /**
     * The addon type.
     *
     * @var string
     */
    protected $type = null;

    /**
     * The addon slug.
     *
     * @var string
     */
    protected $slug = null;

    /**
     * The addon vendor.
     *
     * @var string
     */
    protected $vendor = null;

    /**
     * The addon manager.
     *
     * @var AddonCriteriaInterface
     */
    protected $manager = null;

    /**
     * The addon presenter.
     *
     * @var Presenter
     */
    protected $presenter;

    /**
     * Create a new Addon instance.
     *
     * @param string                 $path    路径
     * @param string                 $addonId addon id
     * @param AddonCriteriaInterface $manager 管理器
     */
    public function __construct(
        string $path,
        string $addonId,
        AddonCriteriaInterface $manager,
        Closure $polyfill = null
    ) {
        $this->path = $path;
        $this->addonId = $addonId;

        list(
            $this->vendor,
            $this->type,
            $this->slug
        ) = explode(':', $this->addonId);

        $this->manager = $manager;

        if (!is_null($polyfill)) {
            $this->polyfill = $polyfill->bindTo($this, get_class($this));
        }
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
     * 创建实例后的其他附加操作，用于不同addon的扩展.
     */
    public function boot()
    {
        /* 比如：视图
        // Add the view
        $this->views->addNamespace(
            $this->getNamespace(),
            [
                $this->application->getResourcesPath(
                    "addons/{$this->getVendor()}/{$this->getSlug()}-{$this->getType()}/views/"
                ),
                $this->getPath('resources/views'),
            ]
        );
        */
    }

    /**
     * Get the addon's presenter.
     *
     * @return Presenter
     */
    /*
    public function getPresenter()
    {
        if (! $this->presenter) {
            $this->presenter = new Presenter($this);
        }

        return $this->presenter;
    }
    */

    /**
     * Get the addon name string.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getNamespace('addon.name');
    }

    /**
     * Get the addon title string.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getNamespace('addon.title')
            ? $this->getNamespace('addon.title')
            : $this->getName();
    }

    /**
     * Get the addon description string.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getNamespace('addon.description');
    }

    /**
     * Get a namespaced key string.
     *
     * @param null $key
     *
     * @return string
     */
    public function getNamespace($key = null)
    {
        return $this->addonId.($key ? '.'.$key : $key);
    }

    /**
     * Return the ID representation (namespace).
     *
     * @return string
     */
    public function getId()
    {
        return $this->getNamespace();
    }

    /**
     * Get the composer json contents.
     *
     * @return mixed|null
     */
    public function getComposerJson()
    {
        $key = $this->getNamespace().'::'.__FUNCTION__;

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $composer = $this->getPath('composer.json');

        if (!file_exists($composer)) {
            return $this->cache[$key] = null;
        }

        if (!$json = array_get($this->cache, $key)) {
            return $this->cache[$key] = json_decode(file_get_contents($composer), true);
        }

        return $json;
    }

    /**
     * Get the composer json contents.
     *
     * @return array|null
     */
    public function getComposerLock()
    {
        $key = $this->getNamespace().'::'.__FUNCTION__;

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $lock = $this->bridge->getBasePath('composer.lock');

        if (!file_exists($lock)) {
            return $this->cache[$key] = null;
        }

        if (!$json = array_get($this->cache, 'composer.lock')) {
            $json = $this->cache['composer.lock'] = json_decode(file_get_contents($lock), true);
        }

        return $this->cache[$key] = array_first(
            $json['packages'],
            function (array $package) {
                return $package['name'] == $this->getPackageName();
            }
        );
    }

    /**
     * Get the README.md contents.
     *
     * @return string|null
     */
    public function getReadme()
    {
        $readme = $this->getPath('README.md');

        if (file_exists($readme)) {
            return file_get_contents($readme);
        }

        return null;
    }

    /**
     * Return the package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->getVendor().'/'.$this->getSlug().'-'.$this->getType();
    }

    /**
     * Get the addon path.
     *
     * @return string
     */
    public function getPath($path = null)
    {
        return $this->path.($path ? '/'.$path : '');
    }

    /**
     * Get the addon slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the addon type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the vendor.
     *
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Get the transformed
     * class to another suffix.
     *
     * @param null $suffix
     *
     * @return string
     */
    public function getTransformedClass($suffix = null)
    {
        $namespace = implode('\\', array_slice(explode('\\', get_class($this)), 0, -1));

        return $namespace.($suffix ? '\\'.$suffix : '');
    }

    /**
     * Return the app path.
     *
     * @param null $path
     */
    public function getAppPath($path = null)
    {
        return ltrim(str_replace($this->manager->getBasePath(), '', $this->getPath($path)), DIRECTORY_SEPARATOR);
    }

    /**
     * 获取本地设置.
     *
     * @return array
     */
    public function getConfigs()
    {
        $directory = $this->getPath('resources/configs');

        if (!is_dir($directory)) {
            return [];
        }

        $results = [];

        /* @var SplFileInfo $file */
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $file = new SplFileInfo($file);

                $key = trim(
                    str_replace(
                        $directory,
                        '',
                        $file->getPath()
                    ).DIRECTORY_SEPARATOR.$file->getBaseName('.php'),
                    DIRECTORY_SEPARATOR
                );
                // Normalize key slashes.
                $key = str_replace('\\', '/', $key);

                $results[$key] = include $file->getPathname();
            }

            closedir($handle);
        }

        return $results;
    }

    /**
     * Get a property value from the object.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = camel_case('get_'.$name);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        $method = camel_case('is_'.$name);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->{$name};
    }

    /**
     * Return whether a property is set or not.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $method = camel_case('get_'.$name);

        if (method_exists($this, $method)) {
            return true;
        }

        $method = camel_case('is_'.$name);

        if (method_exists($this, $method)) {
            return true;
        }

        return isset($this->{$name});
    }

    /**
     * Return the addon as a string.
     *
     * @return string
     */
    /*
    public function __toString()
    {
        return $this->getNamespace();
    }
    */

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    /*
    public function toArray()
    {
        return [
            'id'        => $this->getNamespace(),
            'name'      => $this->getName(),
            'namespace' => $this->getNamespace(),
            'type'      => $this->getType(),
        ];
    }
    */

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

        throw new BadMethodCallException(
            sprintf(
                'Method "%s::%s" does not exist.',
                get_class($this),
                $method
            )
        );
    }
}
