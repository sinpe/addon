<?php 

namespace Sinpe\Addon;

/**
 * Interface Option
 */
interface OptionInterface
{
    /**
     * Get a property.
     *
     * @param string $name  属性名称
     * @param mixed  $value 值
     * 
     * @return mixed
     */
    public function __set(string $name, $value);
    
    /**
     * Get a property.
     *
     * @param string $name 属性名称
     * 
     * @return mixed
     */
    public function __get(string $name);
    
}
