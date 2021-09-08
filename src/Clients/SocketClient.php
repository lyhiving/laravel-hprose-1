<?php

namespace whereof\hprose\Clients;

use Hprose\Socket\Client;


/**
 * Class SocketClient
 * @package whereof\hprose\Clients
 */
class SocketClient extends Client
{
    /**
     * SocketClient constructor.
     * @param null $uris
     * @param bool $async
     */
    public function __construct($uris = null, $async = false)
    {
        parent::__construct($uris, $async);
    }
}