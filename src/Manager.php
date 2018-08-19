<?php

namespace Sinpe\Addon;

use Illuminate\Database\Capsule\Manager as Capsule;
//use Sinpe\Support\Facade;
//use Sinpe\Eloguent\Model;

use Sinpe\Support\Traits\MacroAware as MacroTrait;
use Sinpe\Addon\Module\Entity    as ModuleEntity;
use Sinpe\Addon\Extension\Entity as ExtensionEntity;

/**
 * Class Manager.
 *
 * 管理器
 *
 * 可以通过attachMacro添加whitelists，提供给每个addon可调用的方法
 * 可以通过attachMacro添加invalids，禁止被addon调用的方法
 *
 * @author Sinpe, Inc. <support@sinpe.com>
 * @author Wu Pinlong  <18222544@qq.com>
 */
class Manager implements ManagerInterface
{
    use MacroTrait;

    /**
     * addon types.
     */
    const ADDON_TYPE_E = 'extension';
    const ADDON_TYPE_FT = 'field_type';
    const ADDON_TYPE_M = 'module';
    const ADDON_TYPE_T = 'theme';

    const ADDON_TYPES = [
        self::ADDON_TYPE_E,
        self::ADDON_TYPE_FT,
        self::ADDON_TYPE_M,
        self::ADDON_TYPE_T,
    ];

    /**
     * apply modes.
     */
    const MODE_CONSOLE = 'console'; // 命令行模式
    const MODE_APP = 'app'; // 网页模式

    /**
     * The addon types.
     *
     * @var array
     */
    protected $types;

    /**
     * addon mode.
     *
     * @var string
     */
    private $mode = self::MODE_APP;

    // /**
    //  * The class maps.
    //  *
    //  * @var array
    //  */
    // protected $binds = [
    //     // TODO
    // ];

    /**
     * The addon options.
     *
     * @var Collection
     */
    protected $addons;

    /**
     * The addon loader.
     *
     * @var Loader
     */
    protected $loader;

    /**
     * The modules model.
     *
     * @var ModuleEntity
     */
    protected $module;

    /**
     * The extensions model.
     *
     * @var ExtensionEntity
     */
    protected $extension;

    /**
     * 构造器.
     *
     * @param PolyfillInterface $polyfill  桥接对象
     * @param ModuleEntity      $module    module模型
     * @param ExtensionEntity   $extension extension模型
     */
    final public function __construct(
        PolyfillInterface $polyfill,
        ModuleEntity $module,
        ExtensionEntity $extension
    ) {
        $this->polyfill = $polyfill;
        $this->module = $module;
        $this->extension = $extension;

        // addon types
        $this->types = array_merge(
            self::ADDON_TYPES,
            $this->getConfig('addon.types') ?? []
        );

        $this->paths = new Paths($this);
        $this->addons = new Collection();

        $this->getContainer()->set(
            ManagerInterface::class,
            $this
        );

        // 生命周期函数initialize
        $this->initialize();

        $this->initDb();

        $this->getEventManager()->trigger('init.after');
    }

    /**
     * initialize.
     *
     * 需要额外的初始化，覆盖此方法
     */
    protected function initialize()
    {
        // 更多扩展
    }

    /**
     * banned.
     *
     * 基于不同应用模式，忽略部分addon的加载和初始化
     */
    protected function banned(array $vts, string $mode)
    {
        // 更多扩展
    }

    /**
     * 初始化数据库.
     *
     * 不是使用eloquent的请覆盖重写
     */
    protected function initDb()
    {
        $capsule = new Capsule();
        // 数据库连接配置
        $this->runMacro('db', [$capsule]);
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // 若绑定了事件
        // TODO $capsule->setEventDispatcher($this->getEventManager());

        $capsule->bootEloquent();
    }

    /**
     * 允许外部调用覆盖部分预定义的映射.
     *
     * @return array
     */
    /*
    public function maps($overrides = [])
    {
        return array_merge($this->binds, $overrides);
    }
    */

    /**
     * Get the addon id.
     *
     * @param string $vendor 服务商
     * @param string $type   addon类型
     * @param string $slug   addon名称
     *
     * @return string
     */
    protected function getAddonId($vendor, $type, $slug)
    {
        return "{$vendor}:{$type}:{$slug}";
    }

    /**
     * Set addon run mode.
     *
     * @return static
     */
    public function setMode(string $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * 获取addon命名空间或者给key附加addon的命名空间.
     *
     * @param Option $addonInfo addon信息集
     * @param string $key       待添加addon命名空间的key
     *
     * @return string
     */
    public function getAddonNamespace(Option $addonInfo, string $key = null)
    {
        return $this->getAddonId(
            $addonInfo->vendor,
            $addonInfo->type,
            $addonInfo->slug
        ).($key ? '.'.$key : $key);
    }

    /**
     * 管理器执行addon的注册.
     *
     * @param bool $reload 是否重新导入addon，比如开发模式时可以设置为true，以达到每次正确加载
     */
    public function register($reload = false)
    {
        $enabled = $this->getEnabledAddonNamespaces();
        $installed = $this->getInstalledAddonNamespaces();

        // 每一个addon的路径，即包含addon文件夹名称的完整路径
        if ($reload) {
            $paths = $this->reload();
        } else {
            $paths = $this->paths->all();
        }

        // Iterate all of the addons.
        foreach ($paths as $path) {
            $vts = $this->getVtsByPath($path);

            // 根据不同使用模式，忽略某些addon的加载和初始化
            if ($this->banned($vts, $this->mode) === false) {
                continue;
            }

            $addonId = $this->getAddonId($vts['vendor'], $vts['type'], $vts['slug']);

            $this->initAddon(
                $path,
                $addonId,
                in_array($addonId, $enabled),
                in_array($addonId, $installed)
            );
        }

        $this->getEventManager()->trigger(
            'register.after',
            $this,
            ['addons' => $this->addons]
        );
    }

    /**
     * 重新载入addon，比如新发布到生产环境时，可以调用此方法重新导入addon各路径.
     */
    public function reload()
    {
        $paths = $this->paths->all();

        if (!$this->loader) {
            $this->loader = new Loader();
        }

        $this->loader->load($paths)
            ->register();
        //->dump();

        return $paths;
    }

    /**
     * Register an addon.
     *
     * @param string $path      addon路径
     * @param string $addonId   addonid，三段，点号
     * @param bool   $enabled   是否有效
     * @param bool   $installed 是否已经安装
     *
     * @return array
     */
    protected function initAddon(
        string $path,
        string $addonId,
        bool $enabled,
        bool $installed
    ) {
        list($vendor, $type, $slug) = explode(':', $addonId);

        $addonName = $slug.'-'.$type;

        // addon类名
        $addonClass = studly_case($vendor).
            '\\'.studly_case($slug).studly_case($type).'\\Addon';
        $providerClass = $addonClass.'Provider';
        $criteriaClass = $addonClass.'Criteria';

        $addonInfo = new Option(
            [
                'addonId' => $addonId,
                'name' => $addonName,
                'class' => $addonClass,
                'path' => $path,
                'type' => $type,
                'slug' => $slug,
                'vendor' => $vendor,
            ],
            $this
        );

        if (!class_exists($criteriaClass)) {
            $criteriaClass = AddonCriteria::class;
        }

        $addonInfo->criteria = new $criteriaClass($type, $this);

        // If the addon supports states - set the state now.
        if ($type === self::ADDON_TYPE_M || $type === self::ADDON_TYPE_E) {
            $addonInfo->enabled = $enabled;
            $addonInfo->installed = $installed;
        }

        $container = $this->getContainer();

        $container->set(
            $addonClass,
            function ($container) use ($addonInfo) {
                // polyfill 用于addon提供特殊的对接需求给集成系统调用，
                // 比如: addon提供了i18n，这时i18n将需要和系统做结合，那么此时需要通过一个innovation
                $polyfill = $this->getPolyfill($obj->getVendor(), $addonInfo->name, 'init');

                $obj = new $addonInfo->class(
                    $addonInfo->path,
                    $addonInfo->addonId,
                    $addonInfo->criteria,
                    $polyfill
                );

                // 载入addon包的配置到系统
                foreach ($obj->getConfigs() as $key => $value) {
                    $this->setConfig(
                        "{$addonInfo->addonId}.{$key}",
                        $value
                    );
                }

                // 系统层面对addon包配置的覆盖
                foreach ($this->getConfigOverrides($obj->getVendor(), $addonInfo->name) as $key => $value) {
                    $this->setConfig(
                        "{$addonInfo->addonId}.{$key}",
                        array_replace(
                            $this->getConfig("{$addonInfo->addonId}.{$key}", []),
                            $value
                        )
                    );
                }

                $this->getEventManager()->trigger(
                    'addon.init.after',
                    $this,
                    ['addon' => $obj]
                );

                return $obj;
            }
        );

        // Continue loading things.
        if (!class_exists($providerClass)) {
            if ($this->isDebug()) {
                throw new \Exception('"'.$providerClass.'" missing.');
            } else {
                return;
            }
        }

        if (!$this->isDebug()) {
            // module addon
            if (($addonInfo->type == self::ADDON_TYPE_M
                || $addonInfo->type == self::ADDON_TYPE_E)
                && !$addonInfo->enabled
            ) {
                return;
            }
        }

        // polyfill 用于addon提供特殊的对接需求给集成系统调用，
        // 比如: addon提供了i18n，这时i18n将需要和系统做结合，那么此时需要通过一个innovation
        $polyfill = $this->getPolyfill($vendor, $addonName, 'register');

        $provider = new $providerClass(
            $addonInfo->criteria,
            $polyfill
        );

        $addonInfo->provider = $provider;

        $provider->register();

        $this->getEventManager()->trigger(
            'addon.register.after',
            $this,
            ['addon' => $addonInfo]
        );

        $this->addons->put($addonId, $addonInfo);
    }

    /**
     * Get namespaces for enabled addons.
     *
     * @return array
     */
    protected function getEnabledAddonNamespaces()
    {
        return array_merge(
            $this->module->getEnabledNamespaces()->all(),
            $this->extension->getEnabledNamespaces()->all()
        );
    }

    /**
     * Get namespaces for installed addons.
     *
     * @return array
     */
    protected function getInstalledAddonNamespaces()
    {
        return array_merge(
            $this->module->getInstalledNamespaces()->all(),
            $this->extension->getInstalledNamespaces()->all()
        );
    }

    /**
     * 根据addon的路径名称，获取vendor、type、slug.
     *
     * @param string $path addon路径
     *
     * @return array
     */
    protected function getVtsByPath(string $path)
    {
        // path: .../<vendor name>/<addon name>-<addon type>
        // example: .../sinpe/test-module
        $vendor = strtolower(basename(dirname($path)));

        $name = strtolower(basename($path));
        $splitPos = strpos($name, '-');
        $slug = substr($name, 0, $splitPos);
        $type = substr($name, $splitPos + 1);

        return [
            'vendor' => $vendor,
            'type' => $type,
            'slug' => $slug,
        ];
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
        // 调用innovation方法
        if (method_exists($this->polyfill, $method)) {
            return call_user_func_array([$this->polyfill, $method], $arguments);
        }

        throw new BadMethodCallException(
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
        switch ($name) {
            case 'mode':
                return $this->mode;
        }

        throw new Exception(
            sprintf(
                'Property "%s::%s" does not exist.',
                get_class($this),
                $name
            )
        );
    }
}
