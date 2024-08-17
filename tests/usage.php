<?php

use Mohachi\Xwoole\Http\Server;
use OpenSwoole\Core\Psr\Response;

require_once __DIR__ . "/../vendor/autoload.php";

$server = new Server("localhost", 1111);

$server->get("/", function()
{
    return new Response("welcome\n");
});

$server->start();
