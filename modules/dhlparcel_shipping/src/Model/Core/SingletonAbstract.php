<?php

namespace DHLParcel\Shipping\Model\Core;

abstract class SingletonAbstract
{
    private static $instances = [];

    /**
     * Returns a singleton instance of the called class
     * @return static
     */
    public static function instance()
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}
