<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

/**
 * Constructs the SSE data format and flushes that data to the client.
 *
 * @param string $id Timestamp/id of this connection.
 * @param string $msg Line of text that should be transmitted.
 */
function sendMsg($id, $msg) {
  echo "id: $id" . PHP_EOL;
  echo "data: $msg" . PHP_EOL;
  echo PHP_EOL;
  ob_flush();
  flush();
}

$serverTime = time();

sendMsg($serverTime, 'server time: ' . date("h:i:s", time()));

// header('Content-Type: text/event-stream');
// header('Cache-Control: no-cache');
// header('Connection: keep-alive');
// header('X-Accel-Buffering: no');//Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

// include './vendor/autoload.php';

// use Hhxsv5\SSE\SSE;
// use Hhxsv5\SSE\Update;

// //example: push messages to client


// (new SSE())->start(new Update(function () {
//     $id = mt_rand(1, 1000);
//     $newMsgs = [
//         [
//             'id'      => $id,
//             'title'   => 'title' . $id,
//             'content' => 'content' . $id,
//         ],
//     ];//get data from database or service.
//     if (!empty($newMsgs)) {
//         return json_encode(['newMsgs' => $newMsgs]);
//     }
//     return false;//return false if no new messages    
// }), 'new-msgs');

