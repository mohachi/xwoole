<?php

namespace Mohachi\Xwoole\Http\Functionality\Routing;

use Closure;
use OpenSwoole\Core\Psr\Response;
use Psr\Http\Message\ServerRequestInterface;

trait Routing
{
    private $routes = [
        "GET" => [],
        "POST" => [],
    ];
    
    public function get(string|array $pattern, Closure|string $handler)
    {
        if( is_string($pattern) )
        {
            $this->routes["GET"][] = new Route($pattern, $handler);
            return;
        }
        
        foreach( $pattern as $ptrn )
        {
            $this->routes["GET"][] = new Route($ptrn, $handler);
        }
    }
    
    public function post(string|array $pattern, Closure|string $handler)
    {
        if( is_string($pattern) )
        {
            $this->routes["POST"][] = new Route($pattern, $handler);
            return;
        }
        
        foreach( $pattern as $ptrn )
        {
            $this->routes["POST"][] = new Route($ptrn, $handler);
        }
    }
    
    public function any(string|array $pattern, Closure|string $handler)
    {
        if( is_string($pattern) )
        {
            $route = new Route($pattern, $handler);
            $this->routes["GET"][] = $route;
            $this->routes["POST"][] = $route;
            return;
        }
        
        foreach( $pattern as $ptrn )
        {
            $route = new Route($ptrn, $handler);
            $this->routes["GET"][] = $route;
            $this->routes["POST"][] = $route;
        }
    }
    
    public function match(string $method, string $path): ?Route
    {
        foreach( $this->routes[$method] as $route )
        {
            if( $route->match($path) )
            {
                return $route;
            }
        }
        
        return null;
    }
    
    public function getHandler(): callable
    {
        return function(ServerRequestInterface $request)
        {
            $route = $this->match($request->getMethod(), $request->getUri()->getPath());
            
            if( null !== $route )
            {
                $args = [$request, ...array_values($route->getArguments())];
                $result = call_user_func_array($route->handler, $args);
            }
            
            if( empty($result) )
            {
                dump("no response => repond with 500");
                return new Response("", 500);
            }
            
            return $result;
        };
    }
}
