<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Twilio\Rest\Client;
use App\User;

class botController extends Controller
{
    public function messageListener(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        try {
            $message = $this->decodeCommand($from, $body);
            $this->sendWhatsAppMessage($message, $from);
        } catch (Exception $error) {
          $message = "There was an error!";
          $this->sendWhatsAppMessage($message, $from);
        }
        return;

    }

    public function sendWhatsAppMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = getenv('TWILIO_WHATSAPP_NUMBER');
        $account_sid = getenv('TWILIO_SID');
        $auth_token = getenv('TWILIO_AUTH_TOKEN');

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }

    public function decodeCommand(string $phone, string $command) 
    {
        $user = User::where('phone', $phone)->first();
        $command_array = explode(' ', $command);
        $action = '';
        $params = array();
        $message = "";
        if ($user)
        {
            $message = "You're registered. Congratulations, you can read!";
        }else
        {
            if (strtolower($command_array[0]) == 'reg')
            {
                if (count($command_array) < 4)
                {
                    $message = "Please send us a valid command message! The proper registration command is REG YourFirstName YourSurname UserType. An Example is REG KUDA MOYO READER or REG TATENDA MHUKA AUTHOR";
                }else
                {
                    $isActionPerformed = $this->performAction('REG', array('firstname' => strtolower($command_array[1]), 'surname' => strtolower($command_array[2]), 'phone' => $phone, 'user_type' => strtolower($command_array[3])));
                    if ($isActionPerformed)
                    {
                        $message = "Your account has been created. Send show help to see the list of commands you can send.";
                    }else
                    {
                        $message = "There was an error creating your account. We apologize for any inconvenience caused.";
                    }
                }
            }else
            {
                $message = $this->getHelp(false);
            }
        }

        return $message;

    }

    public function getHelp(bool $isRegistered)
    {
        if ($isRegistered)
        {
            $message = "";
        }else
        {
            $message = "";
        }

        return $message;
    }

    public function performAction(string $action, $params=array())
    {
        $isActionPerformed = false;
        switch ($action)
        {
            case "REG":
                $user = new User();
                $user->name = $params['firstname'].' '.$params['surname'];
                $user->firstname = $params['firstname'];
                $user->phone = $params['phone'];
                $user->surname = $params['surname'];
                $user->user_type = $params['user_type'];
                $user-save();
                $isActionPerformed = true;
                break;
            default:
                $isActionPerformed = false;
        }

        return $isActionPerformed;
    }
}
