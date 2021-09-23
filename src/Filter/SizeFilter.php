<?php


namespace whereof\laravel\hprose\Filter;


use Hprose\Filter;
use stdClass;
use whereof\laravel\hprose\Support\LaravelHelper;

/**
 * Class SizeFilter
 * @package whereof\laravel\hprose\Filter
 */
class SizeFilter implements Filter
{
    /**
     * @var
     */
    private $message;

    /**
     * SizeFilter constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @param $data
     * @param stdClass $context
     * @return mixed
     */
    public function inputFilter($data, stdClass $context)
    {
        LaravelHelper::log($this->message . ' input size: ' . strlen($data), \Monolog\Logger::INFO);
        return $data;
    }

    /**
     * @param $data
     * @param stdClass $context
     * @return mixed
     */
    public function outputFilter($data, stdClass $context)
    {
        LaravelHelper::log($this->message . ' output size: ' . strlen($data), \Monolog\Logger::INFO);
        return $data;
    }
}