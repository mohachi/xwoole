<?php

use Mohachi\Xwoole\Http\Server;
use OpenSwoole\Core\Psr\Response;

require_once __DIR__ . "/../vendor/autoload.php";

$server = new Server("localhost", 1111);

$path = __DIR__ . "/public";

// basic route handling
$server->get("/welcome", function()
{
    return new Response("welcome\n");
});

// route to a specific file
$server->get("/", "$path/index.html");

// route to a specific folder
$server->get("/**", $path);

$server->start();
