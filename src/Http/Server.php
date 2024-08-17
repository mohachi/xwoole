<?php

namespace Mohachi\Xwoole\Http;

use Mohachi\Xwoole\Http\Functionality\Routing\Routing;
use OpenSwoole\Core\Psr\Request;
use OpenSwoole\Http\Server as HttpServer;

class Server extends HttpServer
{
    use Routing;
    
    public function handle(callable $callback): bool
    {
        return false;
    }
    
    #[\Override]
    public function start(): bool
    {
        parent::handle($this->getHandler());
        
        return parent::start();
    }
    
}
