<?php

use Mohachi\Xwoole\Http\Psr\Response;
use Mohachi\Xwoole\Http\Psr\ServerRequest;
use Mohachi\Xwoole\Http\Server;

require_once __DIR__ . "/../vendor/autoload.php";

$server = new Server("localhost", 1111);

$path = __DIR__ . "/public";

// basic route handling
$server->get("/welcome", function(ServerRequest $request)
{
    dump($request);
    return new Response("welcome\n");
});

// route to a specific file
$server->get("/", "$path/index.html");

// route to a specific folder
$server->get("/**", $path);

$server->start();
