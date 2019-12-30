<?php
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 5);

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$i = 1;
while ($i <= 10) {
    print "id: $id\n";
    $time = date('r');
    echo "data: The server time is: {$time}\n";
    ob_flush();
    flush();
    usleep(2000000);
    $i++;
}
