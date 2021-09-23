<?php

namespace whereof\laravel\hprose\Facades;

use Illuminate\Support\Facades\Facade;
use whereof\laravel\hprose\Routing\Router;


/**
 * Class HproseRoute
 * @package whereof\laravel\hprose\Facades
 * @method static Router add($action, $name = '', array $options = [])
 * @method static Router addPath($path)
 * @method static Router getClassMethodArgs()
 * @see https://github.com/hprose/hprose-php/wiki/06-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8
 */
class HproseRoute extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hprose.router';
    }
}
