<?php

require_once '../../vendor/autoload.php';

if(file_exists('../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();
}


class kurl
{
    public function kirl(string $leaves)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://slack.com/api/chat.postMessage',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{\n\t\"channel\":\"" . getenv('SLACK_CHANNEL_ID') . "\",\n\t\"text\":\"" . $leaves ."\n> ".date('F d, Y H:i:s') . " \"\n}",
            CURLOPT_HTTPHEADER => ['Accept: */*',
                'Accept-Encoding: gzip, deflate',
                'Authorization: Bearer ' . getenv('SLACK_OAUTH_BOT_TOKEN'),
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Type: application/json',
                'Host: slack.com',
                'cache-control: no-cache'
                ],
            ]
        );
        curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
        {
            echo 'cURL Error #:' . $err;
        }
    }
}

$h_response = base64_encode(file_get_contents('php://input'));

if(strlen($h_response)) {
    $r = new Kurl();
    echo $r->kirl($h_response);
}
