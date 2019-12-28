<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
$i = 1;
while ($i <= 10) {
    $time = date('r');
    echo "data: The server time is: {$time}\n\n";
    ob_flush();
    flush();
    usleep(2000000);
    $i++;
}
