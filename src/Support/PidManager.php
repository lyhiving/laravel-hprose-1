<?php


namespace whereof\laravel\hprose\Support;


/**
 * Class PidManager
 * @package whereof\laravel\hprose\Support
 */
class PidManager
{
    use Singleton;
    /**
     * @var
     */
    protected $file;


    /**
     * PidManager constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * 进程id
     * @return int
     */
    public function getPid()
    {
        if (is_readable($this->file)) {
            return (int)file_get_contents($this->file);
        }
        return 0;
    }

    /**
     * 守护进程
     * @param callable $func
     */
    public function daemon(callable $func)
    {
        switch (pcntl_fork()) {
            case -1:
                die('fork process failed');
            case 0:
                file_put_contents($this->file, posix_getpid());
                // 开启子进程成功, 做相应操作
                call_user_func($func);
                exit;
        }
    }

    /**
     * 停止
     */
    public function stop()
    {
        posix_kill($this->getPid(), SIGTERM);
        unlink($this->file);
    }
}