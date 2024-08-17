<?php

use Mohachi\Xwoole\Http\Server;
use OpenSwoole\Core\Psr\Request;
use OpenSwoole\Core\Psr\Response;
use OpenSwoole\Core\Psr\Stream;

require_once __DIR__ . "/vendor/autoload.php";

$server = new Server("localhost", 1111);

$server->get("/", function(Request $request)
{
    return new Response("welcome\n");
});

$server->start();
