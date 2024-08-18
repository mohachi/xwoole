<?php

namespace Mohachi\Xwoole\Http;

use Mohachi\Xwoole\Http\Functionality\Serving;
use OpenSwoole\Http\Server as OpenSwooleHttpServer;

class Server extends OpenSwooleHttpServer
{
    use Serving;
}
