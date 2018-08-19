<?php 

namespace Sinpe\Addon;

/**
 * Class Option
 */
class Option implements OptionInterface
{
    /**
     * @var array addon options
     */
    private $items;

    /**
     * @var ManagerInterface manager
     */
    protected $manager;

    /**
     * Create a new Collection instance.
     *
     * @param array            $items   options
     * @param ManagerInterface $manager addon management
     */
    public function __construct(
        array $items, 
        ManagerInterface $manager
    ) {
        $this->items   = $items;
        $this->manager = $manager;
    }

    /**
     * Get a property.
     *
     * @param string $name  属性名称
     * @param mixed  $value 值
     * 
     * @return mixed
     */
    public function __set(string $name, $value)
    {   
        return ($this->items[$name] = $value);
    }

    /**
     * Get a property.
     *
     * @param string $name 属性名称
     * 
     * @return mixed
     */
    public function __get(string $name)
    {   
        if (array_key_exists($name, $this->items)) {
            return $this->items[$name];
        }

        throw new \Exception('unknow property "'.$name.'"');
    }
}
