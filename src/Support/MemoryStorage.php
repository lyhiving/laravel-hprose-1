<?php


namespace whereof\laravel\hprose\Support;


/**
 * Class MemoryStorage
 * @package whereof\laravel\hprose\Support
 */
class MemoryStorage
{
    use Singleton;
    /**
     * @var array
     */
    protected $data;

    /**
     * @param $data
     * @return mixed
     */
    public function push($data)
    {
        $this->data[] = $data;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->data;
    }
}