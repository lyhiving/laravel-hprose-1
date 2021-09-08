<?php


namespace whereof\hprose\Filter;

use Hprose\Filter;
use stdClass;

/**
 * 运行时间统计
 * Class StatFilter
 * @package whereof\hprose\Filter
 */
class StatFilter implements Filter
{

    /**
     * @param $data
     * @param stdClass $context
     * @return mixed
     */
    public function inputFilter($data, stdClass $context)
    {
        // TODO: Implement inputFilter() method.
        $this->stat($context);
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
        $this->stat($context);
        return $data;
    }

    /**
     * @param stdClass $context
     */
    public function stat(stdClass $context)
    {
        if (isset($context->userdata->starttime)) {
            $t = microtime(true) - $context->userdata->starttime;
        } else {
            $context->userdata->starttime = microtime(true);
        }
    }
}