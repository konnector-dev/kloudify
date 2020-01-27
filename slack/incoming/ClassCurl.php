<?php

require_once '../../vendor/autoload.php';

if(file_exists('../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();
}


class ClassCurl
{
    public function kirl(string $text)
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
            CURLOPT_POSTFIELDS => $text,
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
        $resuel = curl_exec($curl);
        print_r(curl_getinfo($resuel));
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
        {
            echo 'cURL Error #:' . $err;
        }
    }
}