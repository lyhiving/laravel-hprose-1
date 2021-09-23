<?php


namespace whereof\laravel\hprose\Support;


/**
 * Trait Singleton
 * @package whereof\laravel\hprose\Support
 */
trait Singleton
{
    private static $instance;

    /**
     * @param mixed ...$args
     * @return static
     */
    static function getInstance(...$args)
    {
        if (!isset(static::$instance)) {
            static::$instance = new static(...$args);
        }
        return static::$instance;
    }
}