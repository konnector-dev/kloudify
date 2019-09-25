<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;

class InfoController extends Controller
{
    private $_konnectFirestoreClient = [];

    public function __construct()
    {
        $this->_konnectFirestoreClient = new FirestoreClient(
            [
                'projectId' => env('FIRESTORE_PROJECT_ID', ''),
                'apiKey' => env('FIRESTORE_WEB_API_KEY', '')
            ]
        );
    }

    public function i()
    {
        phpinfo();
        die();
    }

    public function fs()
    {
        $data_count = rand(10, 20);
        for ($i = 1; $i <= $data_count; $i++) {
            $konnection = $this->_konnectFirestoreClient->collection('qaings')->document(sha1(rand() . time()));
            $konnection->set([
                'user_id' => (int) date('YzHisu'),
                'ip_address' => @$_SERVER['REMOTE_ADDR'],
                'added' => date('F d, Y H:i:s'),
                'timezone_added' => date('F d, Y H:i:s') . ' - ' . date('e'),
            ]);
        }
        die('!');
    }

    public function fr()
    {
        $konnection = $this->_konnectFirestoreClient->collection('qaings');
        $docs = $konnection->orderBy('added', 'desc')->limit(10)->documents();
        $_logins = [];
        foreach ($docs as $doc) {
            if ($doc->exists()) {
                $_logins[] = $doc->data();
            }
        }
        return $_logins;
    }
}
