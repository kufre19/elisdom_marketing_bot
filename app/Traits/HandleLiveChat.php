<?php

namespace App\Traits;

use App\Http\Controllers\vendor\Chatify\MessagesController;
use App\Models\ChatRequest;
use App\Models\Session;
use App\Models\User;
use App\Models\WaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait HandleLiveChat
{
    use MessagesType, SendMessage;
    //    if chat turned on and text is sent to this index then send the text to chat app to pass to chatify and pusher**
    // i want you to take the text from the chat app and send it to chatify and pusher**
    // next when chat request is created i want you to later on write notification to tell admin about new message 
    // then when request is accepted need for you to send a hello message with admin name before a message is continued**

    /**
     *  not complete 
     */
    public function index_live_chat($message = "")
    {

        $session_model = new Session();
        $chat_model = new ChatRequest();
        $customer_wa_phone = $this->userphone;
        $message = $this->user_message_original;
        $session = $session_model->where('whatsapp_id', '=', $this->userphone)->first();
        if ($session) {
            if ($session->live_chat == 1) {
                // continue chatting
                $chatting_with = $session->chatting_with;
                $this->continue_chat_session($message, $customer_wa_phone, $chatting_with);
            } else {
                $session->live_chat = 1;
                $session->save();
                $this->new_chat_request($this->userphone);
            }
        }
    }


    public function new_chat_request($user)
    {
        // create a new chat request session
        $chat_request = new ChatRequest();
        // $chat_request->assigned_to = $customer_reps;
        $chat_request->customer_id = $user;
        $chat_request->save();

        // update the chat session column of chatting_with


    }


    /**
     * this will continue already started chat session
     */

    public function continue_chat_session($message, $phone, $chatting_with)
    {
        if($message == "" || $message == " ")
        {
            http_response_code(200);
            exit();
        }

        $user_model = new WaUser();
        $user = $user_model->where("phone", $phone)->first();

        $chatiffy_controller = app()->make(MessagesController::class);
        $request = new Request();
        
        $response = $chatiffy_controller->Botsend($request->create(
            route("bot.send.message"),
            "POST",
            ["message" => $message, "sender_id" => $user->id, "recipient_id" => $chatting_with]
        ));


        // return redirect()->route("bot.send.message",["message"=>$message,"sender_id"=>$user->id,"recipient_id"=>$chatting_with]);

    }

    /**
     * 
     */
    public function auto_admin_greet_message($admin_id, $customer_wa_id)
    {
        $user_model = new User();
        $user = $user_model->where("id", $admin_id)->first();
        $name = $user->name;
        $greeting_text = "Hi Hello my name is {$name}, how may help you today?";
        $message = $this->make_text_message($greeting_text, $customer_wa_id);
        $this->send_post_curl($message);
    }

    public function auto_admin_end_message($admin_id, $customer_wa_id)
    {
        $user_model = new User();
        $user = $user_model->where("id", $admin_id)->first();
        $name = $user->name;
        $greeting_text = "Your chat with {$name}, was ended";
        $message = $this->make_text_message($greeting_text, $customer_wa_id);
        $this->send_post_curl($message);
    }

    public function send_message_to_admin()
    {

    }
    public function is_live_chat_on($phone)
    {
        $session_model = new Session();
        $session = $session_model->where('whatsapp_id', '=', $phone)->first();
        if($session->live_chat == 1)
        {
            return true;
        }

        return false;
    }

    public function send_message_to_user($message,$phone)
    {

        $message = $this->make_text_message($message,$phone);
        $this->send_post_curl($message);
      
    }
}
