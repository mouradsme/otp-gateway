<?php 

namespace App\Services;
use Illuminate\Support\Facades\Http;

class InfobipService {
    
    protected static $req;

    protected static function init() {
        self::$req = Http::withHeaders([
            'Authorization' => 'App ' . env('INFOBIP_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    public static function send($to, $from, $text) {
        // Initialize the request if not already done
        if (!self::$req) {
            self::init();
        }
        
        $response = self::$req->post(env('INFOBIP_URL') . '/sms/2/text/advanced',  [
            'messages' => [
                [
                    'destinations' => [
                        ['to' => $to],
                    ],
                    'from' => $from,
                    'text' => $text,
                ]
            ]
        ]);

        if ($response->successful()) {
            $result = array('status' => 'success', 'response' => $response->body());
        } else {
            $result = array('status' => 'error', 'response' => 'Unexpected HTTP status: ' . $response->status() . ' ' . $response->reason());
        }

        return json_encode($result);
    }
}
