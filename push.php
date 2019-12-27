<?php

include './vendor/autoload.php';

// use Hhxsv5\SSE\SSE;
// use Hhxsv5\SSE\Update;

//example: push messages to client

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('Content-Encoding: none');
header('X-Accel-Buffering: no');//Nginx: unbuffered responses suitable for Comet and HTTP streaming applications
class Event
{
    protected $id;
    protected $type;
    protected $data;
    protected $retry;
    protected $comment;

    /**
     * Event constructor.
     * @param array $event [id=>id,type=>type,data=>data,retry=>retry,comment=>comment]
     */
    public function __construct(array $event)
    {
        $this->id = isset($event['id']) ? $event['id'] : null;
        $this->type = isset($event['type']) ? $event['type'] : null;
        $this->data = isset($event['data']) ? $event['data'] : null;
        $this->retry = isset($event['retry']) ? $event['retry'] : null;
        $this->comment = isset($event['comment']) ? $event['comment'] : null;
    }

    // public function __toString()
    // {
    //     $event = [];
    //     strlen($this->comment) > 0 AND $event[] = sprintf(': %s', $this->comment);//:comments
    //     strlen($this->id) > 0 AND $event[] = sprintf('id: %s', $this->id);
    //     strlen($this->retry) > 0 AND $event[] = sprintf('retry: %s', $this->retry);//millisecond
    //     strlen($this->type) > 0 AND $event[] = sprintf('event: %s', $this->type);
    //     strlen($this->data) > 0 AND $event[] = sprintf('data: %s', $this->data);
    //     return implode("\n", $event) . "\n\n";
    // }
    public function tring()
    {
        $event = [];
        strlen($this->comment) > 0 AND $event[] = sprintf(': %s', $this->comment);//:comments
        strlen($this->id) > 0 AND $event[] = sprintf('id: %s', $this->id);
        strlen($this->retry) > 0 AND $event[] = sprintf('retry: %s', $this->retry);//millisecond
        strlen($this->type) > 0 AND $event[] = sprintf('event: %s', $this->type);
        strlen($this->data) > 0 AND $event[] = sprintf('data: %s', $this->data);
        return implode("\n", $event) . "\n\n";
    }
}
class SSE
{
    public function start(Update $update, $eventType = null, $milliRetry = 2000)
    {        
        while (true) {
            $changedData = $update->getUpdatedData();
            if ($changedData !== false) {
                $event = [
                    'id'    => uniqid('', true),
                    'type'  => $eventType,
                    'data'  => (string)$changedData,
                    'retry' => $milliRetry,
                ];
            } else {
                $event = [
                    'comment' => 'no update',
                ];
            }
            
            $f = new Event($event);
            echo $f->tring();
            ob_implicit_flush(true);
            ob_end_flush();
            // if the connection has been closed by the client we better exit the loop
            if (connection_aborted()) {
                return;
            }
            sleep($update->getCheckInterval());
        }
    }

}


class Update
{
    /**
     * @var callable This callback is used to check whether data changed
     */
    protected $updateCallback;

    /**
     * @var int interval(s) of check
     */
    protected $checkInterval;

    public function __construct(callable $updateCallback, $checkInterval = 3)
    {
        $this->updateCallback = $updateCallback;
        $this->checkInterval = $checkInterval;
    }

    public function getCheckInterval()
    {
        return $this->checkInterval;
    }

    /**
     * Get the changed data
     * @return mixed|false return false if no changed data
     */
    public function getUpdatedData()
    {
        return call_user_func($this->updateCallback);
    }
}
$data = (new SSE())->start(new Update(function () {
    return '--------'. mt_rand(1, 1000). "\n\n";
}));
// echo 'data: '. '--------'. mt_rand(1, 1000). "\n\n";
flush();