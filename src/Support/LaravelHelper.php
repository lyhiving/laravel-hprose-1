<?php


namespace whereof\laravel\hprose\Support;

use Illuminate\Support\Facades\Log;


/**
 * Class LaravelHelper
 * @package whereof\laravel\hprose\Support
 */
class LaravelHelper
{
    /**
     * @param bool $abb
     * @return string
     */
    public static function version($abb = false)
    {
        if (app() instanceof \Illuminate\Foundation\Application) {
            return $abb ? 'laravel' : "laravel (" . app()->version() . ")";
        }
        if (app() instanceof \Laravel\Lumen\Application) {
            return $abb ? 'lumen' : app()->version();
        }
        return '';
    }

    /**
     * @return mixed
     */
    public static function pidfile()
    {
        return config('hprose.server.daemon.pid', dirname(__FILE__, '3') . '/hprose:socket.pid');
    }

    /**
     * @param $message
     * @param string $level
     * @param array $context
     */
    public static function log($message, string $level = 'error', array $context = [])
    {
        if (in_array('hprose', array_keys(config('logging.channels')))) {
            Log::channel('hprose')->log($level, $message, $context);
            return;
        }
        Log::log($level, $message, $context);
    }
}