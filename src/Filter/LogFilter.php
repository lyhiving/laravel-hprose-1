<?php


namespace whereof\hprose\Filter;


use Hprose\Filter;
use stdClass;
use whereof\hprose\Support\LaravelHelper;

/**
 * 日志跟踪调试
 * Class LogFilter
 * @package whereof\hprose\Filter
 */
class LogFilter implements Filter
{
    /**
     * @param $data
     * @param stdClass $context
     * @return mixed
     */
    public function inputFilter($data, stdClass $context)
    {
        // TODO: Implement inputFilter() method.
        LaravelHelper::log($data, 'debug');
        return $data;
    }

    /**
     * @param $data
     * @param stdClass $context
     * @return mixed
     */
    public function outputFilter($data, stdClass $context)
    {
        // TODO: Implement outputFilter() method.
        LaravelHelper::log($data, 'debug');
        return $data;
    }
}