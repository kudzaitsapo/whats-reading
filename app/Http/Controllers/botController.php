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
            //$message = $this->decodeCommand($from, $body);
            $phone_array = explode(':', $from);
            $message = "Your phone number is ".$phone_array[1];
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
        $message = "";
        if ($user)
        {
            if (strtolower($command_array[0]) != 'reg')
            {
                if ($command_array[0] != '+' || $command_array[0] != '#')
                {
                    
                }else
                {
                    $message = "Adding novels and commenting are not yet supported. Please wait until we are finished ";
                }
                
            }else
            {
                $message = $this->getHelp(true);
            }
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
                        $message = "Your account has been created. Send show help to see the list of messages you can send to start reading novels.";
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
        $message = "";
        if ($isRegistered)
        {
            $message = "To view all novels, please send the following command:\n";
            $message .= "SHOW NOVELS ALL \n";
            $message .= "To show your subscribed novels, please send the following command:\n";
            $message .= "In order to view all chapters of a certain novel, send the following command:\n";
            $message .= "SHOW NovelCode chapters \t";
            $message .= "for example, SHOW AX011 CHAPTERS\n";
            $message .= "To read a specific chapter, send the following command: \n";
            $message .= "SHOW NovelCode ChapterNumber \t";
            $message .= "for example, SHOW AX011 1 \n";
            $message .= "NB: You can only read novels you have subscribed to!!!!! \n";
        }else
        {
            $message = "To register for our services, send the following message: \n";
            $message .= "REG YourFirstname YourSurname UserType. There are two types of user types: author and reader.\n";
            $message .= "For example: REG KUDA MOYO READER, will register your number as Kuda Moyo and as a reader.";
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
                $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
                $user->save();
                $isActionPerformed = true;
                break;
            case "ADD":
                break;
            case "COMMENT": 
                break;
            default:
                $isActionPerformed = false;
        }

        return $isActionPerformed;
    }
}
