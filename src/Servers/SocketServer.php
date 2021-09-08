<?php

namespace whereof\hprose\Servers;

use Hprose\Socket\Server;


/**
 * Class SocketServer
 * @package whereof\hprose\Servers
 */
class SocketServer extends Server
{
    /**
     * SocketServer constructor.
     * @param null $uri
     */
    public function __construct($uri = null)
    {
        parent::__construct($uri);
        $this->uris = [];
        if ($uri) {
            $this->addListener($uri);
        }
    }
}
