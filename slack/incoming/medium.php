<?php
require_once 'ClassCurl.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class Medium {

    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
    
    public function __construct()
    {
        $this->consumer_key = getenv('TWITTER_CONSUSER_KEY');
        $this->consumer_secret = getenv('TWITTER_SECRET_KEY');
        $this->access_token = getenv('TWITTER_ACCESS_TOKEN');
        $this->access_token_secret = getenv('TWITTER_ACCESS_TOKEN_SECRET');
    }

    public function getTweet()
    {
        $connection = new TwitterOAuth($this->consumer_key , $this->consumer_secret, $this->access_token, $this->access_token_secret);
        $content = $connection->get("account/verify_credentials");
        $statuses = $connection->get("statuses/user_timeline", ["screen_name" => "medium", "count" => 1, "exclude_replies" => true]);
        return $this->jsonFormatting($statuses);
    }

    private function jsonFormatting($data)
    {
        $article_link = $data[0]->entities->urls[0]->url;
        $medium_image = $data[0]->user->profile_image_url;

        return '{
            "channel" : "' . getenv('SLACK_CHANNEL_ID') . '",
            "username" : "Jatinder",
            "icon_emoji" : ":chart_with_upwards_trend:",
            "blocks": [
                {
                    "type": "section",
                    "text": {
                        "type": "mrkdwn",
                        "text": "New article published on Medium:"
                    }
                },
                {
                    "type": "section",
                    "block_id": "section567",
                    "text": {
                        "type": "mrkdwn",
                        "text": "<'.$article_link.'|Go to Medium>"
                    },
                    "accessory": {
                        "type": "image",
                        "image_url": "'.$medium_image.'",
                        "alt_text": "Medium Image"
                    }
                }
            ]
        }';
    }
}

$medium = new Medium;
$data = $medium->getTweet();
if(strlen($data)) {
    $curl = new ClassCurl();
    echo $curl->kirl($data);
}