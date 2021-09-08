<?php


namespace whereof\hprose\Support;

use Illuminate\Support\Facades\Log;


/**
 * Class LaravelHelper
 * @package whereof\hprose\Support
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