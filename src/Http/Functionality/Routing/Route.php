<?php

namespace Mohachi\Xwoole\Http\Functionality\Routing;

use Closure;
use Exception;
use OpenSwoole\Core\Psr\Response;
use OpenSwoole\Core\Psr\ServerRequest;
use OpenSwoole\Core\Psr\Stream;

class Route
{
    
    private $arguments = [];
    private $subtitutions = [
        
        // Super Global pattern, example: '/**'
        "~(/\\\\\*(\\\\\*)+)+~" => "(?:.+?)?",
        
        // Global pattern, example: '*'
        "~\\\\\*~" => "[^/]+?",
        
        // Parameter pattern, example: '{any}'
        "~\\\\{([\da-z][\w_]*?)\\\\}~i" => "(?<$1>[^/]+?)"
    ];
    
    readonly string $pattern;
    readonly Closure $handler;
    
    public function __construct(
        string $pattern,
        Closure|string $handler
    )
    {
        $pattern = preg_replace(
            array_keys($this->subtitutions),
            array_values($this->subtitutions),
            preg_quote($pattern, "~")
        );
        
        if( null === $pattern )
        {
            throw new Exception("Invalid route pattern");
        }
        
        if( is_string($handler) )
        {
            $path = realpath($handler);
            
            if( false == $path )
            {
                throw new Exception("invalid path '$handler'");
            }
            
            if( is_file($path) )
            {
                $handler = fn() => new Response(new Stream($path));
            }
            else $handler = function(ServerRequest $request) use ($path)
            {
                $path = $path . $request->getUri()->getPath();
                
                if( is_file($path) )
                {
                    return new Response(new Stream($path));
                }
            };
        }
        
        $this->handler = $handler;
        $this->pattern = "~^$pattern$~";
    }
    
    public function match(string $path): bool
    {
        $result = preg_match($this->pattern, $path, $matches);
        $this->arguments = array_filter($matches, fn($k) => is_string($k), ARRAY_FILTER_USE_KEY);
        return $result;
    }
    
    public function getArguments()
    {
        return $this->arguments;
    }
}

