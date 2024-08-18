<?php

use Mohachi\Xwoole\Http\Server;
use OpenSwoole\Core\Psr\Response;
use OpenSwoole\Core\Psr\ServerRequest;
use OpenSwoole\Core\Psr\Stream;

require_once __DIR__ . "/../vendor/autoload.php";

$server = new Server("localhost", 1111);

// $server->get("/", function()
// {
//     return new Response("welcome\n");
// });

$path = __DIR__ . "/public";
// $server->get("/index.html", function() use ($path)
// {
//     return new Response(new Stream("$path/index.html"));
// });

$server->get("/home/**/ahmed", function(ServerRequest $request)
{
    dump($request->getUri()->getPath());
    return new Response("special\n");
});

$server->get("/home/**", function(ServerRequest $request)
{
    dump($request->getUri()->getPath());
    return new Response("home\n");
});

$server->start();
