<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

// function sendMsg($id, $msg) {
//   echo "id: $id" . PHP_EOL;
//   echo "data: $msg" . PHP_EOL;
//   echo PHP_EOL;
//   ob_flush();
//   flush();
// }
// $serverTime = time();
// sendMsg($serverTime, 'server time: ' . date("h:i:s", time()));

// header('Content-Type: text/event-stream');
// header('Cache-Control: no-cache');
// header('Connection: keep-alive');
// header('X-Accel-Buffering: no');//Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

include './vendor/autoload.php';

use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\Update;

//example: push messages to client


(new SSE())->start(new Update(function () {
    $id = 1;
    $newMsgs = [
        [
            'id'      => $id,
            'title'   => 'title' . $id,
            'content' => 'content' . $id,
        ],
    ];//get data from database or service.
    if (!empty($newMsgs)) {
        return json_encode(['newMsgs' => $newMsgs]);
    }
    return false;//return false if no new messages 
    ob_flush();
    flush();   
}), 'new-msgs');

