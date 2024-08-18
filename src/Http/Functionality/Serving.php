<?php

namespace Mohachi\Xwoole\Http\Functionality;

use Mohachi\Xwoole\Http\Functionality\Routing\Routing;
use Mohachi\Xwoole\Http\Psr\ServerRequest;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

trait Serving
{
    use Routing;
    
    #[\Override]
    public function handle(callable $callback): bool
    {
        return false;
    }
    
    #[\Override]
    public function setHandler($handler): bool
    {
        return false;
    }
    
    #[\Override]
    public function start(): bool
    {
        $this->on("request", function(Request $sq, Response $sp)
        {
            $callback = $this->getHandler();
            $pq  = ServerRequest::createFromOpenSwooleRequest($sq);
            ($callback($pq))->propagateToOpenSwooleResponse($sp);
        });
        
        return parent::start();
    }
}
