<?php

namespace Mohachi\Xwoole\WebSocket;

use Mohachi\Xwoole\Http\Functionality\Serving;
use OpenSwoole\WebSocket\Server as OpenSwooleWebSocketServer;

class Server extends OpenSwooleWebSocketServer
{
    use Serving;
}
