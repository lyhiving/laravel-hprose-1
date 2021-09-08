<?php

namespace whereof\hprose\Commands;

/**
 * Class SocketCommand
 * @package whereof\hprose\Commands
 */
class SocketCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $signature = 'hprose:socket';

    /**
     * @var string
     */
    protected $description = 'hprose rpc socket 服务';

    /**
     *
     */
    public function handle()
    {
        $this->printLog();
        app('hprose.socket.server')->start();
    }
}
