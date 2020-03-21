<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Twilio\Rest\Client;

class botController extends Controller
{
    public function messageListener(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        try {
            $message = "You sent = $body";
            $this->sendWhatsAppMessage($message, $from);
        } catch (Exception $error) {
          $message = "There was an error: ".$error->getMessage();
          $this->sendWhatsAppMessage($message, $from);
        }

    }

    public function sendWhatsAppMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = getenv('TWILIO_WHATSAPP_NUMBER');
        $account_sid = getenv('TWILIO_SID');
        $auth_token = getenv('TWILIO_AUTH_TOKEN');

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }
}
