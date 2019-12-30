<?php
@set_time_limit(0); // Disable time limit
// Prevent buffering
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 5);
// while (ob_get_level() != 0) {
//     ob_end_flush();
// }
// ob_implicit_flush(1);

/* ultility function for sending SSE messages */

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//header('Transfer-encoding: chunked');
header('X-Accel-Buffering: no');
header('Connection: keep-alive');

$counter = 1;

while (1) {
    // Every second, send a "ping" event.

    $curDate = date('r');
    print("id: {$counter}\n");
    //print("event: ping\n");
    echo "data: The server time is: {$curDate}\n\n";

    //print('data: "time": "' . $curDate . '"');
    //  print("\n\n");

    // Send a simple message at random intervals.

    ob_flush();
    flush();
    usleep(2000000);
    $counter++;
}
