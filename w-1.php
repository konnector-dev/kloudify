<?php
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 5);

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//header('Transfer-encoding: chunked');
header('X-Accel-Buffering: no');
header('Connection: keep-alive');

$i = 1;
while ($i <= 10) {
    print "id: $i\n";
    $time = date('r');
    print "data: The server time is: {$time}\n\n";
    flush();
    if (ob_get_level()) {
        ob_flush();
    }
    usleep(2000000);
    $i++;
}
