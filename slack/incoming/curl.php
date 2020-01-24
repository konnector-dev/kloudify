<?php

use GuzzleHttp\Client;


if(file_exists('../../.env')) {
    require_once '../../vendor/autoload.php';
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

$url = getenv('GET_URL');

$client = new Client();
$response = $client->request('GET', $url);

$leaves = '';
if($response->getStatusCode() == 200) {
    $leaves = $response->getBody();
}

if(strlen($leaves)) {
    $r = new Kurl();
    echo $r->kirl($leaves);
}
