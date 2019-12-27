<?php

include './vendor/autoload.php';

use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\Update;

//example: push messages to client

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');//Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

$data = (new SSE())->start(new Update(function () {
    return '--------'. mt_rand(1, 1000). "\n\n";
}));
// echo 'data: '. '--------'. mt_rand(1, 1000). "\n\n";
flush();