<?php

namespace Mohachi\Xwoole\Http\Functionality\Routing;

use Closure;
use Exception;
use OpenSwoole\Core\Psr\Request;
use OpenSwoole\Core\Psr\Response;
use OpenSwoole\Core\Psr\Stream;

class Route
{
    
    private $arguments = [];
    private $subtitutions = [
        "~(/\\\\\*(\\\\\*)+)+~" => "(?:/.+?)?",
        "~\\\\\*~" => "[^/]+?",
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

